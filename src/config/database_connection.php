<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        // Si ya existe una conexión, la usamos
        if (self::$pdo === null) {
            $config = parse_ini_file(__DIR__ . '/../config/db_config.ini', true);

            if (!$config) {
                die("No se pudo leer el archivo de configuración.");
            }

            $dbConfig = $config['database'];
            $host = $dbConfig['host'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];
            $dbname = $dbConfig['dbname'];
            $port = $dbConfig['port'];
            $charset = $dbConfig['charset'];

            try {
                $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=$charset";
                self::$pdo = new PDO($dsn, $username, $password);

                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
