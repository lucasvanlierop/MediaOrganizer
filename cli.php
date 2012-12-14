<?php
//TODO: Add check for dev mode
// Debug functions.
function printr($r, $s = '')
{

    if (!empty($s)) {
        $top = "<div style='background-color:whitesmoke;font-weight:bold;'>" . $s;
        $bottom = '</div>';
    }
    else
    {
        $top = '';
        $bottom = '';
    }

    echo $top . '<pre style="text-align:left;">' . print_r($r, true) . '</pre>' . $bottom . "\n";
}

function __autoload($className)
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
}

$musicOrganizer = new \MediaOrganizer\MusicOrganizer();
$musicOrganizer->run();
