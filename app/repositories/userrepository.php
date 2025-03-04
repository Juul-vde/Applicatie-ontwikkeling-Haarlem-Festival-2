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

    public function getUserById(int $id): ?User
    {
        $stmt = $this->connection->prepare(
            'SELECT id, role, username, password, email, image, phone, fullname, registration_date 
             FROM User WHERE id = :id LIMIT 1'
        );
    
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
    
        return $data ? $this->mapToUserModel($data) : null;
    }
    
    public function updateUser(User $user): bool
    {
        $stmt = $this->connection->prepare(
            'UPDATE User SET username = :username, email = :email, phone = :phone, 
            fullname = :fullname, image = :image WHERE id = :id'
        );

        return $stmt->execute([
            ':username' => $user->username,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':fullname' => $user->fullname,
            ':image' => $user->image, // This will be null when deleting the profile picture
            ':id' => $user->id
        ]);
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

    public function createUser($user)
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO User (fullname, username, email, password, phone) 
             VALUES (:fullName, :username, :email, :hashedPassword, :phone)'
        );
        $stmt->execute([
            ':fullName' => $user->fullname,
            ':username' => $user->username,
            ':email' => $user->email,
            ':hashedPassword' => $user->password,
            ':phone' => $user->phone
        ]);
    }
    
    public function checkUserExists($email, $username)
    {
        $stmt = $this->connection->prepare(
            'SELECT id FROM User WHERE email = :email OR username = :username'
        );

        $stmt->execute([
            ':email' => $email,
            ':username' => $username,
        ]);

        return $stmt->fetch() !== false;
    }
}