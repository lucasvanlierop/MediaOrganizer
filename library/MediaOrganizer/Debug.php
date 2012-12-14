<?php
namespace MediaOrganizer;

class Debug
{
    public function printR($r, $s = '')
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
}
