<?php
function __autoload($className)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
}

$musicOrganizer = new \MediaOrganizer\MusicOrganizer();
$musicOrganizer->run();
