<?php
require_once __DIR__ . "/../services/ServiceReparation.php";
$controller = new Controller();

if (isset($_POST["action"])) {
    $action = $_POST["action"];
    if ($action === "consult") {
        $controller->getReparation();
    } elseif ($action === "insert") {
        $controller->insertReparation();
    } else {
        echo "Acción no válida.";
    }
} else {
    echo "No se ha enviado ninguna acción.";
}


class Controller
{

    function getReparation()
    {
        require_once __DIR__ . "/../View/ViewReparation.php";

        $reparationId = $_POST["reparation_id"];

        $service = new ServiceReparation();
        $reparation = $service->getReparation($reparationId);


        $view = new ViewReparation();
        $view->render($reparation);
    }

    function insertReparation()
    {
        require_once __DIR__ . "/../View/ViewReparation.php";

        $status = $_POST["status"];
        $name = $_POST["name"];
        $registerDate = $_POST["registerDate"];
        $licensePlate = $_POST["licensePlate"];

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
            $photo = $_FILES["photo"]["name"];
            $tmpPhoto = $_FILES["photo"]["tmp_name"];

            $uploadDir =  "/uploads";
            $targetPath = $uploadDir . basename($photo);

            if (move_uploaded_file($tmpPhoto, $targetPath)) {
                echo "Foto subida correctamente.";
            } else {
                echo "Error al subir la foto.";
                $photo = null;
            }
        } else {
            $photo = null;
        }
        $service = new ServiceReparation();
        $reparation = $service->insertReparation($status, $name, $registerDate, $licensePlate, $photo);

        $view = new ViewReparation();
        $view->render($reparation);
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
                return $result->fetch_object();
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

        $sql = "INSERT INTO workshop.reparation (status, name, registerDate, licensePlate, photo) VALUES (?, ?, ?, ?, ?)";
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
}
