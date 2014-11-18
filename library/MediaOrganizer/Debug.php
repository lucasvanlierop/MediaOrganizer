<?php
namespace MediaOrganizer;

/**
 * Debug helper, should be phased out in favor of a logger
 *
 * Class Debug
 * @package MediaOrganizer
 */
class Debug
{
    /**
     * @param string $r
     * @param string $s
     * @return void
     */
    public function printR($r, $s = '')
    {
        if (!empty($s)) {
            $top = "<div style='background-color:whitesmoke;font-weight:bold;'>" . $s;
            $bottom = '</div>';
        } else {
            $top = '';
            $bottom = '';
        }

        echo $top . '<pre style="text-align:left;">' . print_r($r, true) . '</pre>' . $bottom . "\n";
    }
}
