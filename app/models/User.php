<?php
// ============================================================
//  app/models/User.php
// ============================================================

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, phone, role)
            VALUES (:name, :email, :password, :phone, 'customer')
        ");
        $ok = $stmt->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':phone'    => $data['phone'] ?? null,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool)$stmt->fetch();
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
    }
}
