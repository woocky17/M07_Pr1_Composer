<?php
require_once '../Model/Reparation.php';

use Ramsey\Uuid\Uuid;

class ServiceReparation
{
    public function getReparation($idReparation)
    {
        $logger = MyLogger::createLogger("database");

        $connection = new ControllerDataBase;

        $mysqli = $connection->connect();

        $sql = "SELECT id, status, name, registerDate, licensePlate, photo FROM Reparation WHERE id = ?";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("s", $idReparation);

        try {
            $logger->info("Ejecutando consulta para obtener reparación con ID: $idReparation");

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $reparation =  $result->fetch_object();
            } else {
                $logger->warning("No se encontraron resultados para la reparación con ID: $idReparation");
            }

            $stmt->close();
        } catch (Exception $e) {
            $logger->error("Error al ejecutar la consulta para la reparación con ID: $idReparation. Error: " . $e->getMessage());
        }

        if (!$reparation) {
            return null;
        }


        return new Reparation(
            $reparation->id,
            $reparation->status,
            $reparation->name,
            $reparation->registerDate,
            $reparation->licensePlate,
            $reparation->photo
        );
    }


    public function insertReparation($status, $name, $registerDate, $licensePlate, $photo)
    {
        try {
            $uuid = Uuid::uuid4();

            "UUID: %s\nVersion: %d\n";
            $id =  $uuid->toString();

            $logger = MyLogger::createLogger("database");

            $connection = new ControllerDataBase;
            $mysqli = $connection->connect();

            $sql = "INSERT INTO workshop.reparation (id, status, name, registerDate, licensePlate, photo) 
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param("ssssss", $id, $status, $name, $registerDate, $licensePlate, $photo);

            try {
                if (!$stmt->execute()) {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }
                $logger->info("Reparación insertada correctamente con ID: " . $stmt->insert_id, [
                    'id' => $id,
                    'status' => $status,
                    'name' => $name,
                    'registerDate' => $registerDate,
                    'licensePlate' => $licensePlate,
                    'photo' => $photo,
                ]);
                $stmt->close();
            } catch (Exception $e) {
                $logger->error("Error de inserción: " . $e->getMessage(), [
                    'id' => $id,
                    'status' => $status,
                    'name' => $name,
                    'registerDate' => $registerDate,
                    'licensePlate' => $licensePlate,
                    'photo' => $photo,
                ]);
                throw new Exception("Error de inserción: " . $e->getMessage());
            }

            return new Reparation($id, $status, $name, $registerDate, $licensePlate, $photo);
        } catch (Exception $e) {
            return new ErrorMessage("Error al insertar reparación: " . $e->getMessage());
        }
    }
}
