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
        $photo = $_FILES["photo"];

        $service = new ServiceReparation();
        $service->insertReparation($status, $name, $registerDate, $licensePlate, $photo);
    }
}
