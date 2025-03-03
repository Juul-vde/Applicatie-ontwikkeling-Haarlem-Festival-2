<?php

namespace App\Repositories;

use App\Models\User;
use App\Enums\Role;
class UserRepository extends Repository
{
    public function getUserByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare(
            'SELECT id, role, username, password, email, image, phone, fullname, registration_date FROM User WHERE Email = :email LIMIT 1'
        );

        $stmt->execute([
            ':email' => $email,
        ]);

        $data = $stmt->fetch();

        if ($data) {
            return $this->mapToUserModel($data);
        }

        return null;
    }

    public function mapToUserModel(array $data): User
    {
        $user = new User();

        // Map the fetched data to the User model properties
        $user->id = (int) $data['id'];
        $roleValue = (int) $data['role'];

        // Safely map the role to the Role enum using tryFrom
        $user->role = Role::tryFrom($roleValue) ?? Role::USER; // Default to USER if the value is invalid

        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->email = $data['email'];
        $user->image = $data['image'];
        $user->phone = $data['phone'];
        $user->fullname = $data['fullname'];

        // Check if 'registration_date' is not null or empty before converting to DateTime
        if (!empty($data['registration_date'])) {
            $user->registration_date = new \DateTime($data['registration_date']);
        } else {
            $user->registration_date = null; // Handle null dates appropriately
        }

        return $user;
    }

}