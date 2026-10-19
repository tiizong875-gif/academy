<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function connect(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                if (ini_get('display_errors')) {
                    die("Database Connection Error: " . $e->getMessage());
                } else {
                    die("A database error occurred. Please contact the administrator.");
                }
            }
        }
        return self::$instance;
    }
}
