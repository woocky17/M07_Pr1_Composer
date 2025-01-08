<?php

class ControllerDataBase
{
    private $mysqli;

    // Método para conectarse a la base de datos
    public function connect()
    {
        $config = parse_ini_file('../config/db_config.ini', true)['database'];

        $this->mysqli = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['dbname'],
            $config['port']
        );

        if ($this->mysqli->connect_error) {
            die("Error de conexión: " . $this->mysqli->connect_error);
        }
    }

    // Método para obtener el objeto mysqli
    public function getMysqli()
    {
        return $this->mysqli;
    }
}
