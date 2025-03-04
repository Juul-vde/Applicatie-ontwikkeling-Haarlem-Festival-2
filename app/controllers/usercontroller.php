<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Enums\Role;
use App\Utils\Mailer;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];


            $user = $this->userService->getUserByMail($email);

            if ($user) {
                // Step 3: Generate a reset token
                $token = bin2hex(random_bytes(32));

                // Step 4: Save the token in the database with an expiration date
                $this->userService->storeResetToken($user->id, $token);

                // Step 5: Send an email to the user with the reset link
                $resetLink = "http://localhost/user/reset-password?token=" . $token;
                $this->sendResetEmail($user, $resetLink);

                // Step 6: Provide feedback to the user
                echo "If the email exists in our system, we have sent a password reset link to it.";
            } else {
                // Optional: For security reasons, you might still show the same message to prevent email enumeration
                echo "If the email exists in our system, we have sent a password reset link to it.";
            }
        } else {
            // Render the reset-password form (GET request)
            include __DIR__ . '/../views/user/reset-password.php';
        }

    }

    public function sendResetEmail($user, $resetLink)
    {
        $mailer = new Mailer();

        // Proper HTML structure for the email body
        $body = "
        <html>
        <body>
            <h3>Please click the following link to reset your password:</h3>
            <a href='" . $resetLink . "'>" . $resetLink . "</a>
        </body>
        </html>
    ";

        // Send the email
        $mailer->sendEmail(
            $user->email,
            $user->fullname,
            "Haarlem Festival - Password Reset",
            $body,  // The email body with HTML
            null,
            null
        );
    }
}