<?php
// ============================================================
//  app/models/Payment.php
// ============================================================

class Payment {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO payments (booking_id, proof_image, payment_method, status)
            VALUES (:booking_id, :proof_image, :payment_method, 'pending')
        ");
        $ok = $stmt->execute([
            ':booking_id'      => $data['booking_id'],
            ':proof_image'     => $data['proof_image'],
            ':payment_method'  => $data['payment_method'],
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function findByBooking(int $bookingId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE booking_id = :id LIMIT 1");
        $stmt->execute([':id' => $bookingId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getPending(): array {
        $stmt = $this->db->query("
            SELECT p.*, b.total_price, b.booking_date, b.start_time, b.end_time,
                   u.name AS user_name, u.email AS user_email,
                   l.name AS lapangan_name
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN users u ON b.user_id = u.id
            JOIN lapangan l ON b.lapangan_id = l.id
            WHERE p.status = 'pending'
            ORDER BY p.created_at ASC
        ");
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT p.*, b.total_price, b.booking_date,
                   u.name AS user_name,
                   l.name AS lapangan_name
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN users u ON b.user_id = u.id
            JOIN lapangan l ON b.lapangan_id = l.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function verify(int $id, int $adminId, string $status, string $notes = ''): bool {
        $stmt = $this->db->prepare("
            UPDATE payments
            SET status = :status, verified_by = :admin_id, verified_at = NOW(), notes = :notes
            WHERE id = :id
        ");
        return $stmt->execute([
            ':status'   => $status,
            ':admin_id' => $adminId,
            ':notes'    => $notes,
            ':id'       => $id,
        ]);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, b.total_price, b.booking_date, b.start_time, b.end_time, b.user_id,
                   u.name AS user_name, u.email AS user_email,
                   l.name AS lapangan_name
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN users u ON b.user_id = u.id
            JOIN lapangan l ON b.lapangan_id = l.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function countPending(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM payments WHERE status='pending'")->fetchColumn();
    }
}
