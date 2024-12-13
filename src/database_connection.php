<?php
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
    $pdo = new PDO($dsn, $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
