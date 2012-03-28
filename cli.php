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

// @Todo fix auto loading
require_once "MusicOrganizer.php";
require_once "Directory.php";
require_once "File.php";
require_once "File/Mp3.php";
require_once "MetaData.php";
$musicOrganizer = new MusicOrganizer();
$musicOrganizer->run();
