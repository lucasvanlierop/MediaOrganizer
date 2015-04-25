<?php
namespace File\MetaData;

/**
 * @todo make this work
 *
 */
class RemoteRetriever
{
    protected function getMetadataFromApi($file)
    {
        if ($this instanceof \MediaOrganizer\File\Type\Mp3) {
            $comments = findInfoByFilename($file);
        }

        if (empty($comments)) {

            echo "no info: " . $fullFileName . "\n";

            // @todo [check] if file got moved to music clean
            return false;
            $dir = $this->destinationDirectory . '_no-info/';
            $filePath = $dir . $file;

            if (!file_exists($filePath)) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                if (rename($fullFileName, $filePath)) {
                    echo "copied to unknown: " . $filePath . "\n";
                }
            }
            return false;
        }
    }
}
