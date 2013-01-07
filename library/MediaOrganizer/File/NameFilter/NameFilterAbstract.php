<?php
namespace MediaOrganizer\File\NameFilter;

/**
 * @todo add support for unicode
 */
abstract class NameFilterAbstract
{
    /**
     * @param string $name
     * @return mixed|string
     */
    protected function cleanName($name)
    {
        // @todo move 'the ' prefix to  ' (the)' suffix
        // Replace unwanted characters
        $replacements = array(
            '/[.]/'        => '',       // Unknown characters
            '/&/'          => 'and',    // And sign
            '/[^\w().]+/'  => ' '       // Non word characters
        );
        $out = preg_replace(array_keys($replacements), $replacements, $name);

        // Upper case every first word
        $out = ucwords(strtolower(trim($out)));

        // Concatenate every word
        $out = preg_replace('/( )+/', '-', $out);

        return $out;
    }
}
