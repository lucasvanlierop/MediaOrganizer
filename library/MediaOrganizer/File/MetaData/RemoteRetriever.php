<?php
namespace File\MetaData;

/**
 * @todo make this work
 *
 */
class RemoteRetriever
{
    protected function _getMetadataFromApi($file)
    {
        if ($this instanceof \MediaOrganizer\File\Type\Mp3) {
            $comments = findInfoByFilename($file);
        }

        if (empty($comments)) {

            echo "no info: " . $FullFileName . "\n";


            // @todo [check] if file got moved to music clean
            return false;
            $dir = $this->_destinationDirectory . '_no-info/';
            $file_path = $dir . $file;


            if (!file_exists($file_path)) {
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                if (rename($FullFileName, $file_path)) {
                    echo "copied to unknown: " . $file_path . "\n";
                }
            }
            return false;
        }
    }
}
