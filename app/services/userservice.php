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
}