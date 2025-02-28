<?php

namespace App\Repositories;

use App\Models\User;
class UserRepository extends Repository
{
    public function getUserByEmail(string $email): ?User
    {
        // $stmt = $this->connection->prepare(
        //     'SELECT UserID, Username, PasswordHash, Email, FullName, Role, Phone FROM Users WHERE Email = :email LIMIT 1'
        // );

        // $stmt->execute([
        //     ':email' => $email,
        // ]);

        // $data = $stmt->fetch();

        // if ($data) {
        //     return $this->mapToUserModel($data);
        // }

        // return null;
        $user = new User();
        $user->id = 1;
        return $user;
    }

}