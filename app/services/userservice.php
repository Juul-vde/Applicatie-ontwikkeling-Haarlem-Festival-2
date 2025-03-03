<?php
namespace App\Services;

use App\Models\User;
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
}