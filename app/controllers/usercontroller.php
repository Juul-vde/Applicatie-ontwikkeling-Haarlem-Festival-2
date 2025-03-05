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
        $error = $_SESSION['error_message'] ?? null;
        $successMessage = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);

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

    public function dashboard()
    {
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
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'fullname' => trim($_POST['fullname'])
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

    public function forgotpassword()
    {
        $successMessage = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $user = $this->userService->getUserByMail($email);

            if ($user) {
                //Generate a reset token
                $token = bin2hex(random_bytes(32));
                $this->userService->storeResetToken($user->id, $token);

                //Send an email to the user with the reset link
                $resetLink = "http://localhost/user/resetpassword?token=" . $token;
                if ($this->userService->sendResetEmail($user, $resetLink)) {
                    $_SESSION['success_message'] = 'If the email exists in our system, we have sent a password reset link to it.';
                    header('Location: /user/login');
                } else {
                    // Log or handle email failure
                    $_SESSION['error_message'] = 'There was an issue sending the email.';
                    header('Location: /user/login');
                }
            } else {
                $_SESSION['success_message'] = 'If the email exists in our system, we have sent a password reset link to it.';
                header('Location: /user/login');
            }
        } else {
            // Render the reset-password form (GET request)
            include __DIR__ . '/../views/user/reset-password.php';
        }

    }

    public function resetPassword()
    {
        $error = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = $_GET['token'] ?? null;
            // Verify if token exists in the URL and is valid
            if ($token && $this->userService->isResetTokenValid($token)) {
                include __DIR__ . '/../views/user/change-password.php';  // Show the form
            } else {
                $_SESSION['error_message'] = 'Invalid or expired link';
                header('Location: /user/login');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($this->userService->checkPassword($newPassword, $confirmPassword)) {
                if ($this->userService->isResetTokenValid($token)) {

                    $userId = $this->userService->getUserIdByToken($token);
                    $this->userService->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
                    $this->userService->invalidateResetToken($token);

                    $_SESSION['success_message'] = 'Password has been reset successfully, you can now log in.';
                    header('Location: /user/login');
                } else {
                    $_SESSION['error_message'] = 'Invalid or expired link';
                    header('Location: /user/login');
                }
            } else {
                $_SESSION['error_message'] = 'Passwords did not match or were not at least 6 characters long.';
                // Redirect to the reset password page again with the token
                header('Location: /user/resetpassword?token=' . urlencode($token));
            }
        }
    }


}