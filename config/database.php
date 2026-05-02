<?php
/**
 * config/database.php
 * Connexion PDO — Singleton
 * Club Joker
 */

class Database
{
    // ── Paramètres locaux de secours (XAMPP/WAMP) ────────────
    private static $host     = '127.0.0.1';
    private static $dbname   = 'joker_club';
    private static $user     = 'root';
    private static $password = '';
    private static $charset  = 'utf8mb4';

    private static $instance = null;

    // Constructeur privé → empêche l'instanciation directe
    private function __construct() {}

    /**
     * Retourne l'unique instance PDO (Singleton)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host     = self::env('DB_HOST', self::$host);
            $port     = self::env('DB_PORT', '');
            $dbname   = self::env('DB_NAME', self::$dbname);
            $user     = self::env('DB_USER', self::env('DB_USERNAME', self::$user));
            $password = self::env('DB_PASSWORD', self::$password);

            $dsn = "mysql:host={$host}"
                 . ($port !== '' ? ";port={$port}" : '')
                 . ";dbname={$dbname}"
                 . ";charset=" . self::$charset;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $password, $options);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('Connexion a la base de donnees echouee. Verifiez les variables DB_HOST, DB_NAME, DB_USER/DB_USERNAME et DB_PASSWORD.');
            }
        }

        return self::$instance;
    }

    private static function env(string $key, string $default = '')
    {
        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return $value;
        }

        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }

        return $default;
    }
}
