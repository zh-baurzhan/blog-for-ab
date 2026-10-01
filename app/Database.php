<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: '3306';
            $name = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');

            if ($name === false || $user === false || $pass === false) {
                throw new RuntimeException('error database connection');
            }

            $settingPDO = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4", $user, $pass, $settingPDO);
        }
        return self::$pdo;
    }
}
