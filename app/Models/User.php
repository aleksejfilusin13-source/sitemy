<?php

require_once __DIR__ . '/../../core/Database.php';

final class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email, password_hash, created_at
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email, password_hash, phone, city, birth_date, about_me, created_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function create(string $name, string $email, string $passwordHash): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (name, email, password_hash)
             VALUES (:name, :email, :password_hash)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function updatePasswordHash(int $id, string $passwordHash): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users
             SET password_hash = :password_hash
             WHERE id = :id'
        );
        $stmt->execute([
            'password_hash' => $passwordHash,
            'id' => $id,
        ]);
    }

    public static function updateProfile(
        int $id,
        string $name,
        ?string $phone,
        ?string $city,
        ?string $birthDate,
        ?string $aboutMe
    ): void {
        $stmt = Database::connection()->prepare(
            'UPDATE users
             SET name = :name,
                 phone = :phone,
                 city = :city,
                 birth_date = :birth_date,
                 about_me = :about_me
             WHERE id = :id'
        );
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'city' => $city,
            'birth_date' => $birthDate,
            'about_me' => $aboutMe,
            'id' => $id,
        ]);
    }
}
