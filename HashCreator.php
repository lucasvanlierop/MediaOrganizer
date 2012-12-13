<?php
/**
 */
class MusicOrganizer_HashCreator
{
    public function getHashDir() {
        return $hashDir = ROOT_DIR . '_hashes' . '/';
    }


    protected function _createAudioHashSoftLink(\MediaOrganizer\File $file)
    {
        $hash = $file->getHash();
        $command = 'ln -s "' . $FullFileName . '" ' . $this->getHashDir() . $hash;
        exec($command);

        echo "\n" . $command;
    }
}
