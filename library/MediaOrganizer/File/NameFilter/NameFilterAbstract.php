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
        // Replace unwanted characters
        $replacements = array(
            '/[.\']/'        => '',       // Unknown characters
            '/&/'          => 'and',    // And sign
            '/[^\w()]+/'  => ' '       // Non word characters
        );
        $out = preg_replace(array_keys($replacements), $replacements, $name);

        // Upper case every first word
        $out = ucwords(strtolower(trim($out)));

        // Concatenate every word
        $out = preg_replace('/( )+/', '-', $out);

        // suffix 'the'
        if (substr($out, 0, 4) == 'The-') $out = substr($out, 4) . '-(the)';

        return $out;
    }
}
