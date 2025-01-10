<?php
require_once __DIR__ . "/../services/ServiceReparation.php";
require_once __DIR__ . '/../../vendor/autoload.php';

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

            $uploadDir = __DIR__ . "/../../uploads/";
            $targetPath = $uploadDir . basename($photo);

            if (!move_uploaded_file($tmpPhoto, $targetPath)) {
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
        $logger = MyLogger::createLogger("database");

        $connection = new ControllerDataBase;
        $reparation = null;

        $mysqli = $connection->connect();

        $sql = "SELECT id, status, name, registerDate, licensePlate, photo FROM Reparation WHERE id = ?";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("i", $idReparation);

        try {
            $logger->info("Ejecutando consulta para obtener reparación con ID: $idReparation");

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_object();
            } else {
                $logger->warning("No se encontraron resultados para la reparación con ID: $idReparation");
            }

            $stmt->close();
        } catch (Exception $e) {
            $logger->error("Error al ejecutar la consulta para la reparación con ID: $idReparation. Error: " . $e->getMessage());
        }

        return $reparation;
    }

    public static function addReparation($status, $name, $registerDate, $licensePlate, $photo)
    {
        $logger = MyLogger::createLogger("database");

        $connection = new ControllerDataBase;
        $mysqli = $connection->connect();

        $sql = "INSERT INTO workshop.reparation (status, name, registerDate, licensePlate, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("sssss", $status, $name, $registerDate, $licensePlate, $photo);

        try {
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            $logger->info("Reparación insertada correctamente con ID: " . $stmt->insert_id, [
                'status' => $status,
                'name' => $name,
                'registerDate' => $registerDate,
                'licensePlate' => $licensePlate,
                'photo' => $photo,
            ]);
            $stmt->close();
        } catch (Exception $e) {
            $logger->error("Error de inserción: " . $e->getMessage(), [
                'status' => $status,
                'name' => $name,
                'registerDate' => $registerDate,
                'licensePlate' => $licensePlate,
                'photo' => $photo,
            ]);
            throw new Exception("Error de inserción: " . $e->getMessage());
        }
    }
}
