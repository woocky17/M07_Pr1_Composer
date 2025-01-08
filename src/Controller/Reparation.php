<?php

require_once __DIR__ . '/../Controller/ControllerDataBase.php';

class Reparation extends ControllerDataBase
{
    private $id;
    private $status;
    private $name;
    private $registerDate;
    private $licensePlate;
    private $photo;

    public function __construct($reparation)
    {
        $this->id = $reparation->id;
        $this->status = $reparation->status;
        $this->name = $reparation->name;
        $this->registerDate = $reparation->registerDate;
        $this->licensePlate = $reparation->licensePlate;
        $this->photo = $reparation->photo;
    }

    public static function fetchReparationById($idReparation)
    {
        $connection = new ControllerDataBase;
        $reparation = null;

        $connection->connect();

        $sql = "SELECT id, status, name, registerDate, licensePlate, photo FROM Reparation WHERE id = ?";
        $stmt = $connection->getMysqli()->prepare($sql);

        $stmt->bind_param("i", $idReparation);

        try {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_object();

                $reparation = new Reparation($data);
            } else {
                echo "No se encontraron resultados para la reparación con ID: $idReparation";
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error de consulta: " . $e->getMessage();
        }

        return $reparation;
    }

    public static function addReparation($status, $name, $registerDate, $licensePlate, $photo)
    {

        $connection = new ControllerDataBase;

        $connection->connect();

        $sql = `INSERT INTO workshop.reparation (status, name, registerDate, licensePlate, photo) VALUES (?, ?, ?, ?, ?)`;
        $stmt = $connection->getMysqli()->prepare($sql);

        $stmt->bind_param("sssss", $status, $name, $registerDate, $licensePlate, $photo);

        try {
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            echo "Reparación insertada correctamente con ID: " . $stmt->insert_id;
            $stmt->close();
        } catch (Exception $e) {
            throw new Exception("Error de inserción: " . $e->getMessage());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    public function getLicensePlate()
    {
        return $this->licensePlate;
    }

    public function getPhoto()
    {
        return $this->photo;
    }
}
