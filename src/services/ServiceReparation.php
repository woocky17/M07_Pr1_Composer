<?php
require_once '../Model/Reparation.php';

use Ramsey\Uuid\Uuid;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServiceReparation
{
    public function getReparation($idReparation, $role)
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

        if ($role === "client") {
            $manager = new ImageManager(new Driver());
            $base64Data = explode(',', $reparation->photo)[1];
            $imageData = base64_decode($base64Data);

            $image = $manager->read($imageData);
            $image->pixelate(30);

            $type = pathinfo($reparation->photo, PATHINFO_EXTENSION);

            $reparation->photo = 'data:image/' . $type . ';base64,' . base64_encode($image->encode());

            $reparation->licensePlate = str_repeat('*', strlen($reparation->licensePlate));
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

            $manager = new ImageManager(new Driver());
            $image = $manager->read($photo);
            $watermarkText = "UUID: $id\nMatricula: $licensePlate";

            $image->text($watermarkText, 10, $image->height() - 50, function ($font) {
                $font->size(200); // Tamaño del texto
                $font->color('#FFFFFF'); // Color con transparencia
                $font->align('left'); // Alineación horizontal
                $font->valign('bottom'); // Alineación vertical
            });
            $photo = 'data:image/;base64,' . base64_encode($image->encode());


            $connection = new ControllerDataBase;
            $mysqli = $connection->connect();

            $sql = "INSERT INTO workshop.reparation (id, status, name, registerDate, licensePlate, photo) 
            VALUES (?, ?, ?, ?, ?, ?)";


            try {
                $stmt = $mysqli->prepare($sql);

                $stmt->bind_param("ssssss", $id, $status, $name, $registerDate, $licensePlate, $photo);
                if (!$stmt->execute()) {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }
                $logger->info("Reparación insertada correctamente con ID: " . $stmt->insert_id, [
                    'id' => $id,
                    'status' => $status,
                    'name' => $name,
                    'registerDate' => $registerDate,
                    'licensePlate' => $licensePlate,
                ]);
                $stmt->close();
            } catch (Exception $e) {
                $logger->error("Error de inserción: " . $e->getMessage(), [
                    'id' => $id,
                    'status' => $status,
                    'name' => $name,
                    'registerDate' => $registerDate,
                    'licensePlate' => $licensePlate,
                ]);
                throw new Exception("Error de inserción: " . $e->getMessage());
            }



            return new Reparation($id, $status, $name, $registerDate, $licensePlate, $photo);
        } catch (Exception $e) {
            return new ErrorMessage("Error al insertar reparación: " . $e->getMessage());
        }
    }
}
