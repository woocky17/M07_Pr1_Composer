<?php
require_once __DIR__ . "/../services/ServiceReparation.php";
require_once __DIR__ . "/../View/ViewReparation.php";



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
        $reparationId = $_POST["reparation_id"];

        $service = new ServiceReparation();
        $reparation = $service->getReparation($reparationId);


        $view = new ViewReparation();
        $view->render($reparation);
    }

    function insertReparation()
    {
        $status = $_POST["status"];
        $name = $_POST["name"];
        $registerDate = $_POST["registerDate"];
        $licensePlate = $_POST["licensePlate"];

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
            $path = $_FILES["photo"]["tmp_name"];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $photo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $photo = null;
        }
        $service = new ServiceReparation();
        $reparation = $service->insertReparation($status, $name, $registerDate, $licensePlate, $photo);

        $view = new ViewReparation();
        $view->render($reparation);
    }
}
