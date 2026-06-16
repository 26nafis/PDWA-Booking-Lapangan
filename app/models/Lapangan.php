<?php
// ============================================================
//  app/models/Lapangan.php
// ============================================================

class Lapangan {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(string $search = '', int $categoryId = 0): array {
        $sql = "SELECT l.*, c.name AS category_name,
                       COALESCE(AVG(r.rating), 0) AS avg_rating,
                       COUNT(DISTINCT r.id) AS review_count
                FROM lapangan l
                LEFT JOIN categories c ON l.category_id = c.id
                LEFT JOIN reviews r ON l.id = r.lapangan_id
                WHERE l.is_available = 1";

        $params = [];
        if ($search !== '') {
            $sql .= " AND l.name LIKE :search";
            $params[':search'] = "%$search%";
        }
        if ($categoryId > 0) {
            $sql .= " AND l.category_id = :cat";
            $params[':cat'] = $categoryId;
        }

        $sql .= " GROUP BY l.id ORDER BY l.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllAdmin(): array {
        $stmt = $this->db->query("
            SELECT l.*, c.name AS category_name
            FROM lapangan l
            LEFT JOIN categories c ON l.category_id = c.id
            ORDER BY l.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT l.*, c.name AS category_name,
                   COALESCE(AVG(r.rating), 0) AS avg_rating,
                   COUNT(DISTINCT r.id) AS review_count
            FROM lapangan l
            LEFT JOIN categories c ON l.category_id = c.id
            LEFT JOIN reviews r ON l.id = r.lapangan_id
            WHERE l.id = :id
            GROUP BY l.id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getBookedSlots(int $lapanganId, string $date): array {
        $stmt = $this->db->prepare("
            SELECT start_time, end_time FROM bookings
            WHERE lapangan_id = :id AND booking_date = :date
            AND status NOT IN ('cancelled')
        ");
        $stmt->execute([':id' => $lapanganId, ':date' => $date]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int|false {
        $stmt = $this->db->prepare("
            INSERT INTO lapangan (category_id, name, description, price_per_hour, location, image, is_available)
            VALUES (:category_id, :name, :description, :price_per_hour, :location, :image, :is_available)
        ");
        $ok = $stmt->execute([
            ':category_id'    => $data['category_id'],
            ':name'           => $data['name'],
            ':description'    => $data['description'],
            ':price_per_hour' => $data['price_per_hour'],
            ':location'       => $data['location'],
            ':image'          => $data['image'] ?? null,
            ':is_available'   => $data['is_available'] ?? 1,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE lapangan SET
                    category_id = :category_id,
                    name = :name,
                    description = :description,
                    price_per_hour = :price_per_hour,
                    location = :location,
                    is_available = :is_available";

        if (!empty($data['image'])) {
            $sql .= ", image = :image";
        }
        $sql .= " WHERE id = :id";

        $params = [
            ':category_id'    => $data['category_id'],
            ':name'           => $data['name'],
            ':description'    => $data['description'],
            ':price_per_hour' => $data['price_per_hour'],
            ':location'       => $data['location'],
            ':is_available'   => $data['is_available'],
            ':id'             => $id,
        ];
        if (!empty($data['image'])) {
            $params[':image'] = $data['image'];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM lapangan WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM lapangan")->fetchColumn();
    }

    public function getCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    public function getReviews(int $lapanganId): array {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name AS user_name FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.lapangan_id = :id ORDER BY r.created_at DESC
        ");
        $stmt->execute([':id' => $lapanganId]);
        return $stmt->fetchAll();
    }
}
