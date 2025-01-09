<?php
require_once '../Model/Reparation.php';

class ServiceReparation
{
    public function getReparation($idReparation)
    {
        $data = Controller::fetchReparationById($idReparation);

        if (!$data) {
            return null;
        }


        return new Reparation(
            $data->status,
            $data->name,
            $data->registerDate,
            $data->licensePlate,
            $data->photo
        );
    }

    public function insertReparation($status, $name, $registerDate, $licensePlate, $photo)
    {
        try {
            Controller::addReparation($status, $name, $registerDate, $licensePlate, $photo);

            return new Reparation($status, $name, $registerDate, $licensePlate, $photo);
        } catch (Exception $e) {
            return "Error al insertar reparación: " . $e->getMessage();
        }
    }
}
