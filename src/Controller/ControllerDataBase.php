<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../log/MyLogger.php';


class ControllerDataBase
{
    private static $mysqli = null;
    private static $logger = null;

    public static function connect()
    {
        if (self::$logger === null) {
            self::$logger = MyLogger::createLogger('database');
        }

        $config = parse_ini_file('../config/db_config.ini', true);

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

        if (self::$mysqli === null) {
            try {
                self::$mysqli = new mysqli($host, $username, $password, '', $port);

                if (self::$mysqli->connect_error) {
                    die("Error de conexión: " . self::$mysqli->connect_error);
                }

                self::$logger->info("Conexión exitosa a la base de datos: $dbname en el host $host");

                $result = self::$mysqli->query("SHOW DATABASES LIKE '$dbname'");
                if ($result->num_rows === 0) {
                    self::$mysqli->query("CREATE DATABASE `$dbname` CHARACTER SET $charset COLLATE " . $charset . "_general_ci");
                    self::$logger->info("Base de datos '$dbname' creada.");
                }

                self::$mysqli->select_db($dbname);

                $createTableSQL = "
                    CREATE TABLE IF NOT EXISTS `workshop`.`reparation` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `status` VARCHAR(255) NOT NULL,
                        `name` VARCHAR(255) NOT NULL,
                        `registerDate` DATE NOT NULL,
                        `licensePlate` VARCHAR(20) NOT NULL,
                        `photo` TEXT NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=$charset;
                ";

                self::$mysqli->query($createTableSQL);
                self::$logger->info("Tabla 'reparation' verificada o creada exitosamente.");
            } catch (mysqli_sql_exception $e) {
                self::$logger->error("Error de conexión: " . $e->getMessage());
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$mysqli;
    }
}
