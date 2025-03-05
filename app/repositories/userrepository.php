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
            ':image' => $user->image,
            ':id' => $user->id
        ]);
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM User WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function getAllUsers(): array
    {
        $stmt = $this->connection->prepare(
            'SELECT id, role, username, password, email, image, phone, fullname, registration_date 
             FROM User ORDER BY fullname'
        );

        $stmt->execute();
        $users = [];

        while ($data = $stmt->fetch()) {
            $users[] = $this->mapToUserModel($data);
        }

        return $users;
    }

    public function updateUserRole(int $userId, int $role): bool
    {
        $stmt = $this->connection->prepare('UPDATE User SET role = :role WHERE id = :id');
        return $stmt->execute([
            ':role' => $role,
            ':id' => $userId
        ]);
    }

    public function mapToUserModel(array $data): User
    {
        $user = new User();

        $user->id = (int) $data['id'];
        $roleValue = (int) $data['role'];
        $user->role = Role::tryFrom($roleValue) ?? Role::USER;
        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->email = $data['email'];
        $user->image = $data['image'];
        $user->phone = $data['phone'];
        $user->fullname = $data['fullname'];

        if (!empty($data['registration_date'])) {
            $user->registration_date = new \DateTime($data['registration_date']);
        } else {
            $user->registration_date = null;
        }

        return $user;
    }

    public function createUser($user)
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO User (fullname, username, email, password, phone, image) 
             VALUES (:fullName, :username, :email, :hashedPassword, :phone, :image)'
        );
        $stmt->execute([
            ':fullName' => $user->fullname,
            ':username' => $user->username,
            ':email' => $user->email,
            ':hashedPassword' => $user->password,
            ':phone' => $user->phone,
            ':image' => $user->image
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
    public function storeResetToken($id, $token)
    {
        // Calculate token expiration (e.g., 1 hour from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));


        $stmt = $this->connection->prepare(
            'INSERT INTO password_resets (user_id, reset_token, expires_at) 
        VALUES (:user_id, :reset_token, :expires_at)'
        );

        $stmt->execute([
            ':user_id' => $id,
            ':reset_token' => $token,
            ':expires_at' => $expires_at
        ]);

        // Return true if the insertion was successful
        return $stmt->rowCount() > 0;
    }

    public function isResetTokenValid($token)
    {
        $stmt = $this->connection->prepare("SELECT * FROM password_resets WHERE reset_token = :token AND expires_at > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch() ? true : false;
    }

    public function getUserIdByToken($token)
    {
        $stmt = $this->connection->prepare("SELECT user_id FROM password_resets WHERE reset_token = :token");
        $stmt->execute(['token' => $token]);
        $result = $stmt->fetch(); // fetch() returns an associative array by default
        return $result ? $result['user_id'] : null; // Access as array
    }

    public function updatePassword($userId, $hashedPassword)
    {
        $stmt = $this->connection->prepare("UPDATE User SET password = :password WHERE id = :user_id");
        return $stmt->execute([
            'password' => $hashedPassword,
            'user_id' => $userId
        ]);
    }

    public function invalidateResetToken($token)
    {
        $stmt = $this->connection->prepare("DELETE FROM password_resets WHERE reset_token = :token");
        return $stmt->execute(['token' => $token]);
    }
}