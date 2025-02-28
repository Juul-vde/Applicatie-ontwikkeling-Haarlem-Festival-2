<?php
namespace App\Controllers;


class UserController
{
    private $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
        $this->startSession();
    }

    public function login()
    {
        $this->redirectIfLoggedIn();
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
                $_SESSION['isAdmin'] = $user->role === Role::Admin;
                $_SESSION['username'] = $user->username;
                header('Location: /'); // Redirect to home page
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        }

        include __DIR__ . '/../views/user/login.php';
    }

    public function register()
    {
        $this->redirectIfLoggedIn();
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

    public function accountdetails()
    {
        $navigationData = $this->getNavigationData();
        if (!isset($_SESSION['loggedIn'])) {
            header('Location: /user/login'); // Redirect to login if not logged in
            exit;
        }
        $loggedInUser = $this->userService->getUserById($_SESSION['userId']);
        include __DIR__ . '/../views/user/accountdetails.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /'); // Redirect to the home page
        exit;
    }

    public function updateUserInfo()
    {
        if (!isset($_SESSION['loggedIn'])) {
            header('Location: /user/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loggedInUser = $this->userService->getUserById($_SESSION['userId']);
            $userId = $loggedInUser->id;

            $newData = [
                'username' => $_POST['username'] ?? '',
                'fullName' => $_POST['fullname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
            ];

            $updatedFields = array_filter($newData, fn($value, $key) => $loggedInUser->$key !== $value, ARRAY_FILTER_USE_BOTH);

            if (!empty($updatedFields)) {
                $error = $this->userService->updateUserInfo($userId, $updatedFields);

                $_SESSION[$error ? 'error' : 'success'] = $error ?: 'User information updated successfully!';
            } else {
                $_SESSION['error'] = 'No changes detected.';
            }
            header('Location: /user/accountdetails');
            exit;
        }

        header('Location: /user/accountdetails');
        exit;
    }

    public function changepassword()
    {
        if (!isset($_SESSION['loggedIn'])) {
            header('Location: /user/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loggedInUser = $this->userService->getUserById($_SESSION['userId']);

            $currentPassword = $_POST['currentPassword'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            try {
                $this->userService->changePassword($currentPassword, $newPassword, $confirmPassword, $loggedInUser);
                $_SESSION['success'] = 'Password updated successfully!';
            } catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
            header('Location: /user/accountdetails');
            exit;
        }

        header('Location: /user/accountdetails');
        exit;
    }
}


