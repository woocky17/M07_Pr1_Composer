<?php
require_once __DIR__ . "/../services/ServiceReparation.php";
require_once __DIR__ . "/../View/ViewReparation.php";
require_once __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\Image;


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

        if (isset($_POST["reparation_id"]) && isset($_POST["role"])) {
            $reparationId = $_POST["reparation_id"];
            $role = $_POST["role"];

            $service = new ServiceReparation();
            $reparation = $service->getReparation($reparationId, $role);

            $view = new ViewReparation();
            $view->render($reparation);
        } else {
            echo "Error: Los datos no fueron enviados correctamente.";
        }
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
            try {
                // Usamos Intervention Image para cargar y manipular la imagen
                $img = Image::make('public/foo.jpg');
                // Pixelamos la imagen, por ejemplo, con un valor de 10
                $img->pixelate(10); // Ajusta el valor de pixelado según sea necesario

                // Convertimos la imagen pixelada a base64
                $photo = 'data:image/' . $type . ';base64,' . base64_encode($img->encode($type)->getEncoded());
            } catch (Exception $e) {
                echo "Error al procesar la imagen: " . $e->getMessage();
                return;
            }
        } else {
            $photo = null;
        }
        $service = new ServiceReparation();
        $reparation = $service->insertReparation($status, $name, $registerDate, $licensePlate, $photo);

        $view = new ViewReparation();
        $view->render($reparation);
    }
}
