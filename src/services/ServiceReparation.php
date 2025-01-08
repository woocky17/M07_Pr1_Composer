<?php
require_once 'Reparation.php';


class ServiceReparation
{
    public function getReparation($idReparation)
    {
        $data = Reparation::fetchReparationById($idReparation);

        if (!$data) {
            return null;
        }


        return  new Reparation($data);
    }

    public function insertReparation($status, $name, $registerDate, $licensePlate, $photo)
    {
        try {
            Reparation::addReparation($status, $name, $registerDate, $licensePlate, $photo);
        } catch (Exception $e) {
            return "Error al insertar reparación: " . $e->getMessage();
        }
    }
}
