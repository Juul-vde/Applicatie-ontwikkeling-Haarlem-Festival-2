<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Enums\Role;
class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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
                // If you have roles, you can also set them (e.g., isAdmin)
                $_SESSION['isAdmin'] = $user->role === Role::ADMIN;
                header('Location: /'); // Redirect to home page
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        }

        // Include the login view and pass the error or success message
        include __DIR__ . '/../views/user/login.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
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

    public function updateProfile()
    {
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false) {
            header('Location: /user/login');
            exit;
        }

        $userId = $_SESSION['userId'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Combine first and last name if they exist
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

            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $imagePath = '/uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public' . $imagePath);
            }

            if ($this->userService->updateProfile($userId, $data, $imagePath)) {
                $_SESSION['success_message'] = "Profile updated successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to update profile.";
            }

            header("Location: /user/dashboard");
            exit;
        }
    }

    // Changed from edit_profile to editProfile to match PHP method naming conventions
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
        //$this->redirectIfLoggedIn();
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