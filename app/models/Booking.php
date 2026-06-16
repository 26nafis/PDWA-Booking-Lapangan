<?php
// ============================================================
//  app/models/Booking.php
// ============================================================

class Booking {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (user_id, lapangan_id, booking_date, start_time, end_time,
                                  duration_hours, total_price, status, notes)
            VALUES (:user_id, :lapangan_id, :booking_date, :start_time, :end_time,
                    :duration_hours, :total_price, 'pending', :notes)
        ");
        $ok = $stmt->execute([
            ':user_id'        => $data['user_id'],
            ':lapangan_id'    => $data['lapangan_id'],
            ':booking_date'   => $data['booking_date'],
            ':start_time'     => $data['start_time'],
            ':end_time'       => $data['end_time'],
            ':duration_hours' => $data['duration_hours'],
            ':total_price'    => $data['total_price'],
            ':notes'          => $data['notes'] ?? null,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT b.*, l.name AS lapangan_name, l.location, l.price_per_hour, l.image AS lapangan_image,
                   c.name AS category_name, u.name AS user_name, u.email AS user_email, u.phone AS user_phone,
                   p.status AS payment_status, p.proof_image, p.payment_method, p.id AS payment_id
            FROM bookings b
            JOIN lapangan l ON b.lapangan_id = l.id
            JOIN categories c ON l.category_id = c.id
            JOIN users u ON b.user_id = u.id
            LEFT JOIN payments p ON b.id = p.booking_id
            WHERE b.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT b.*, l.name AS lapangan_name, l.image AS lapangan_image,
                   c.name AS category_name,
                   p.status AS payment_status
            FROM bookings b
            JOIN lapangan l ON b.lapangan_id = l.id
            JOIN categories c ON l.category_id = c.id
            LEFT JOIN payments p ON b.id = p.booking_id
            WHERE b.user_id = :user_id
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getAll(string $status = ''): array {
        $sql = "
            SELECT b.*, l.name AS lapangan_name, u.name AS user_name, u.email AS user_email,
                   p.status AS payment_status
            FROM bookings b
            JOIN lapangan l ON b.lapangan_id = l.id
            JOIN users u ON b.user_id = u.id
            LEFT JOIN payments p ON b.id = p.booking_id
        ";
        $params = [];
        if ($status !== '') {
            $sql .= " WHERE b.status = :status";
            $params[':status'] = $status;
        }
        $sql .= " ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE bookings SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function cancel(int $id, int $userId): bool {
        $stmt = $this->db->prepare("
            UPDATE bookings SET status = 'cancelled'
            WHERE id = :id AND user_id = :user_id AND status = 'pending'
        ");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function isSlotTaken(int $lapanganId, string $date, string $start, string $end, int $excludeId = 0): bool {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE lapangan_id = :lapangan_id
              AND booking_date = :date
              AND status NOT IN ('cancelled')
              AND id != :exclude_id
              AND (
                (start_time < :end AND end_time > :start)
              )
        ");
        $stmt->execute([
            ':lapangan_id' => $lapanganId,
            ':date'        => $date,
            ':start'       => $start,
            ':end'         => $end,
            ':exclude_id'  => $excludeId,
        ]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    }

    public function countToday(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()")->fetchColumn();
    }

    public function countPending(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn();
    }

    public function totalRevenue(): float {
        return (float)$this->db->query("
            SELECT COALESCE(SUM(b.total_price), 0) FROM bookings b
            JOIN payments p ON b.id = p.booking_id
            WHERE p.status = 'verified'
        ")->fetchColumn();
    }

    public function hasReviewed(int $bookingId): bool {
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE booking_id = :id");
        $stmt->execute([':id' => $bookingId]);
        return (bool)$stmt->fetch();
    }
}
