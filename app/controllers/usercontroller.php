<?php
namespace App\Controllers;

use App\Services\UserService;

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
                // $_SESSION['isAdmin'] = $user->role === Role::Admin;
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




}


