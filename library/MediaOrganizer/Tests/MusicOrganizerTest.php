<?php

namespace MediaOrganizer\Tests;

use MediaOrganizer\MusicOrganizer;
use PHPUnit_Framework_TestCase;

class MusicOrganizerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldVisitRootDirectoryAndRenameFiles()
    {
        $config = [
            'genre' => [
                'knownDirs' => [
                    'Rock'
                ],
                'dirMapping' => [
                    'rock' => 'Rock'
                ]
            ]
        ];

        // @todo: get rid of filesystem tests when code is refactored
        $resourcesDir = __DIR__ . '/Resources';
        $sourceFile = $resourcesDir . '/source-media/single-track.mp3';
        $targetDir = $resourcesDir . '/target-media';
        $targetFile = $targetDir . '/single-track.mp3';
        $this->createTargetDir($targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileNotExists($targetDir . '/single-track.mp3');
        $this->assertFileExists($targetDir . '/Rock/Foo-Fighters/Everlong.mp3');
    }

    /**
     * @param $targetDir
     */
    private function createTargetDir($targetDir)
    {
        if (file_exists($targetDir)) {
            exec('rm -rf ' . escapeshellarg($targetDir));
        }
        mkdir($targetDir);
    }
}
