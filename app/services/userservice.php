<?php
namespace App\Services;

use App\Models\User;
use App\Enums\Role;
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

        // if ($user && password_verify($password, $user->hashedPassword)) {
        //     return $user; // Authentication successful
        // }

        return $user; // Authentication failed
    }

    public function getUserProfile(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function updateProfile(int $id, array $data, ?string $imagePath): bool
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            return false;
        }

        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->fullname = $data['fullname'];
        $user->image = $imagePath ?? $user->image;

        return $this->userRepository->updateUser($user);
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
        if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) { // Simple phone number validation
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
        // Create the user
        $user = new User();
        $user->fullname = $fullname;
        $user->username = $username;
        $user->password = $hashedPassword;
        $user->phone = $phone;
        $user->email = $email;

        $this->userRepository->createUser($user);

        return null; // No errors
    }

    public function getUserByMail($email)
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function storeResetToken($id, $token)
    {
        return $this->userRepository->storeResetToken($id, $token);
    }
}