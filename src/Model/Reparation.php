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

    public function __construct($id, $status, $name, $registerDate, $licensePlate, $photo)
    {
        $this->id = $id;
        $this->status = $status;
        $this->name = $name;
        $this->registerDate = $registerDate;
        $this->licensePlate = $licensePlate;
        $this->photo = $photo;
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

class ErrorMessage
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
