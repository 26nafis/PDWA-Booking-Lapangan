<?php
// ============================================================
//  app/models/Review.php
// ============================================================

class Review {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO reviews (booking_id, user_id, lapangan_id, rating, comment)
            VALUES (:booking_id, :user_id, :lapangan_id, :rating, :comment)
        ");
        $ok = $stmt->execute([
            ':booking_id'  => $data['booking_id'],
            ':user_id'     => $data['user_id'],
            ':lapangan_id' => $data['lapangan_id'],
            ':rating'      => $data['rating'],
            ':comment'     => $data['comment'] ?? null,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function existsByBooking(int $bookingId): bool {
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE booking_id = :id");
        $stmt->execute([':id' => $bookingId]);
        return (bool)$stmt->fetch();
    }
}
