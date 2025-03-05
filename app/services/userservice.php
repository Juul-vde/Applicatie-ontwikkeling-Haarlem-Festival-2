<?php
namespace App\Services;

use App\Models\User;
use App\Enums\Role;
use App\Utils\Mailer;
use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function authenticateUser(string $email, string $password): ?User
    {
        $user = $this->userRepository->getUserByEmail($email);
        return $user;
    }

    public function getUserProfile(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            return false;
        }

        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->fullname = $data['fullname'];

        if (isset($data['image'])) {
            $user->image = $data['image'];
        }

        return $this->userRepository->updateUser($user);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function updateUserRole(int $userId, int $role): bool
    {
        return $this->userRepository->updateUserRole($userId, $role);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->deleteUser($id);
    }

    public function registerUser($firstName, $lastName, $username, $email, $password, $confirmPassword, $phone)
    {
        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($phone)) {
            return 'All fields are required.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address.';
        }
        if (strlen($username) < 3) {
            return 'Username must be at least 3 characters long.';
        }
        if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
            return 'Invalid phone number.';
        }
        if ($password !== $confirmPassword) {
            return 'Passwords do not match.';
        }
        if (strlen($password) < 6) {
            return 'Password must be at least 6 characters long.';
        }
        if ($this->userRepository->checkUserExists($email, $username)) {
            return 'Email or username already registered.';
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $fullname = $firstName . " " . $lastName;

        $user = new User();
        $user->fullname = $fullname;
        $user->username = $username;
        $user->password = $hashedPassword;
        $user->phone = $phone;
        $user->email = $email;
        $user->image = '/media/Profile_avatar_placeholder.png';

        $this->userRepository->createUser($user);

        return null;
    }

    public function checkPassword($password, $confirmPassword)
    {
        if ($password !== $confirmPassword) {
            return false;
        }
        if (strlen($password) < 6) {
            return false;
        }
        return true;
    }

    public function getUserByMail($email)
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function storeResetToken($id, $token)
    {
        return $this->userRepository->storeResetToken($id, $token);
    }

    // Check if the reset token is valid
    public function isResetTokenValid($token)
    {
        return $this->userRepository->isResetTokenValid($token);
    }
    // Get user ID by reset token
    public function getUserIdByToken($token)
    {
        return $this->userRepository->getUserIdByToken($token);
    }

    // Update the password for a user
    public function updatePassword($userId, $hashedPassword)
    {
        return $this->userRepository->updatePassword($userId, $hashedPassword);
    }

    // Invalidate the reset token after password is changed
    public function invalidateResetToken($token)
    {
        return $this->userRepository->invalidateResetToken($token);
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

        // Send the email and check if it was successful
        if (
            $mailer->sendEmail(
                $user->email,
                $user->fullname,
                "Haarlem Festival - Password Reset",
                $body,  // The email body with HTML
                null,
                null
            )
        ) {
            return true; // Email sent successfully
        } else {
            return false; // Email failed
        }
    }
    public function checkUserExists($email, $username)
    {
        return $this->userRepository->checkUserExists($email, $username);
    }
}
