<?php
// ============================================================
//  app/config/database.php — Koneksi PDO Singleton
// ============================================================

class Database {
    private static ?PDO $instance = null;

    private static string $host   = 'localhost';
    private static string $dbName = 'booking_lapangan_db';
    private static string $user   = 'root';
    private static string $pass   = 'TekniInformasi24';
    private static string $charset = 'utf8mb4';

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::$host .
                   ';dbname=' . self::$dbName .
                   ';charset=' . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                die('<h1>Database Connection Error</h1><p>' . htmlspecialchars($e->getMessage()) . '</p>');
            }
        }
        return self::$instance;
    }
}
