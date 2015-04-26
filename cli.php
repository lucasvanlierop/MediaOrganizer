<?php

require_once "vendor/autoload.php";

$musicOrganizer = new \MediaOrganizer\MusicOrganizer(
    require_once 'config/config.php'
);
$musicOrganizer->run('~/Music/Rock');
