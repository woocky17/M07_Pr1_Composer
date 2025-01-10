<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class MyLogger
{
    public static function createLogger(string $name): Logger
    {
        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler(__DIR__ . '/log.log', Level::Info));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    }
}
