<?php

namespace Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $this->connect();
    }

    private function connect(?string $dbname = null)
    {
        $host = config('database.mysql.host');
        $database = config('database.mysql.database');
        $username = config('database.mysql.username');
        $password = config('database.mysql.password');

        if (!$host || !$username) {
            die("Database connection details are not set in the configuration.");
        }

        try {
            $dsn = "mysql:host={$host}" . ($dbname ? ";dbname={$dbname}" : '');
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            if (!$dbname) $this->selectDB($database);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function selectDB($dbname): PDO
    {
        $this->query("create database if not exists {$dbname}");

        $this->connect($dbname);
        return $this->pdo;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)->execute();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
