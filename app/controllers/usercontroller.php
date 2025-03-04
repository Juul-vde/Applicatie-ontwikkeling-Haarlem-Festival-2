<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Enums\Role;

class UserController
{
    private $userService;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userService = new UserService();
    }

    public function login()
    {
        $error = null;
        $successMessage = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userService->authenticateUser($email, $password);

            if ($user) {
                $_SESSION['userId'] = $user->id;
                $_SESSION['loggedIn'] = true;
                $_SESSION['isAdmin'] = $user->role === Role::ADMIN;
                header('Location: /');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        }

        include __DIR__ . '/../views/user/login.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function deleteAccount()
    {
        if (!isset($_SESSION['loggedIn']) || !isset($_SESSION['userId'])) {
            header('Location: /user/login');
            exit;
        }

        $userId = $_SESSION['userId'];

        if ($this->userService->deleteUser($userId)) {
            session_unset();
            session_destroy();
            header('Location: /');
            exit;
        } else {
            $_SESSION['error_message'] = 'Failed to delete account. Please try again.';
            header('Location: /user/dashboard');
            exit;
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false) {
            header('Location: /user/login');
            exit;
        }
    
        $userId = $_SESSION['userId'] ?? null;
    
        if ($userId) {
            $user = $this->userService->getUserProfile($userId);
        } else {
            $user = null;
        }
    
        $isAdmin = $user && $user->role === Role::ADMIN;
    
        include __DIR__ . '/../views/user/dashboard.php';
    }    

    public function manage() {
        if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
            header('Location: /');
            exit;
        }

        $users = $this->userService->getAllUsers();
        include __DIR__ . '/../views/user/manage.php';
    }

    public function changeRole() {
        if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['userId'] ?? null;
            $role = $_POST['role'] ?? null;

            if ($userId && $role !== null) {
                if ($this->userService->updateUserRole($userId, (int)$role)) {
                    $_SESSION['success_message'] = 'User role updated successfully.';
                } else {
                    $_SESSION['error_message'] = 'Failed to update user role.';
                }
            }
        }

        header('Location: /user/manage');
        exit;
    }

    public function deleteUser() {
        if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['userId'] ?? null;

            if ($userId) {
                if ($this->userService->deleteUser($userId)) {
                    $_SESSION['success_message'] = 'User deleted successfully.';
                } else {
                    $_SESSION['error_message'] = 'Failed to delete user.';
                }
            }
        }

        header('Location: /user/manage');
        exit;
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false) {
            header('Location: /user/login');
            exit;
        }

        $userId = $_SESSION['userId'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            if (empty($fullname) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
                $fullname = trim($_POST['firstName'] . ' ' . $_POST['lastName']);
            }
            
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'fullname' => $fullname
            ];

            // Handle profile picture deletion
            if (isset($_POST['deleteProfilePicture'])) {
                $data['image'] = '/media/Profile_avatar_placeholder.png';
            } else if (!empty($_FILES['image']['name'])) {
                try {
                    // Validate file extension
                    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $_SESSION['error_message'] = "Only JPG, JPEG & PNG files are allowed.";
                        header("Location: /user/editProfile");
                        exit;
                    }

                    // Generate unique filename
                    $filename = 'profile_' . $userId . '_' . uniqid() . '.' . $fileExtension;
                    $uploadDir = dirname(dirname(__DIR__)) . '/app/public/media/';
                    $uploadFile = $uploadDir . $filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $data['image'] = '/media/' . $filename;
                    } else {
                        throw new \Exception('Failed to move uploaded file.');
                    }
                } catch (\Exception $e) {
                    error_log("Error processing image: " . $e->getMessage());
                    $_SESSION['error_message'] = "Failed to process image. Please try again.";
                    header("Location: /user/editProfile");
                    exit;
                }
            }

            if ($this->userService->updateProfile($userId, $data)) {
                $_SESSION['success_message'] = "Profile updated successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to update profile.";
            }

            header("Location: /user/dashboard");
            exit;
        }
    }

    public function editProfile() {
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false) {
            header('Location: /user/login');
            exit;
        }
    
        $userId = $_SESSION['userId'] ?? null;
    
        if ($userId) {
            $user = $this->userService->getUserProfile($userId);
        } else {
            $user = null;
        }
    
        include __DIR__ . '/../views/user/edit-profile.php';
    }

    public function register()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            $phone = trim($_POST['phone'] ?? '');

            $error = $this->userService->registerUser($firstName, $lastName, $username, $email, $password, $confirmPassword, $phone);

            if ($error === null) {
                $_SESSION['success_message'] = 'Registration successful! Please log in.';
                header('Location: /user/login');
                exit;
            }
        }
        include __DIR__ . '/../views/user/register.php';
    }
}