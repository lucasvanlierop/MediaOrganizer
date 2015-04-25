<?php

namespace MediaOrganizer\Tests;

use MediaOrganizer\MusicOrganizer;
use PHPUnit_Framework_TestCase;

class MusicOrganizerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldVisitRootDirectoryAndRenameSingleTrack()
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
     * @test
     */
    public function shouldVisitRootDirectoryAndRenameAlbumTrack()
    {
        $config = [
            'genre' => [
                'knownDirs' => [
                    'Dance/Div'
                ],
                'dirMapping' => [
                    'ambient' => 'Dance/Div'
                ]
            ]
        ];

        // @todo: get rid of filesystem tests when code is refactored
        $resourcesDir = __DIR__ . '/Resources';
        $sourceFile = $resourcesDir . '/source-media/album-track.mp3';
        $targetDir = $resourcesDir . '/target-media';
        $targetFile = $targetDir . '/album-track.mp3';
        $this->createTargetDir($targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileNotExists($targetDir . '/album-track.mp3');
        $this->assertFileExists($targetDir . '/Dance/Div/Enigma/1990-Mcmxc-Ad-(the-Limited-Edition)/001_Sadeness.mp3');
    }

    /**
     * @test
     */
    public function shouldVisitRootDirectoryAndRenameCompilationTrack()
    {
        $config = [
            'genre' => [
                'knownDirs' => [
                    'Dance/Electro'
                ],
                'dirMapping' => [
                    'electro' => 'Dance/Electro'
                ]
            ]
        ];

        // @todo: get rid of filesystem tests when code is refactored
        $resourcesDir = __DIR__ . '/Resources';
        $sourceFile = $resourcesDir . '/source-media/compilation-track.mp3';
        $targetDir = $resourcesDir . '/target-media';
        $targetFile = $targetDir . '/compilation-track.mp3';
        $this->createTargetDir($targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileNotExists($targetDir . '/compilation-track.mp3');
        $this->assertFileExists($targetDir . '/Dance/Electro/Miss-Kittin/2002-Radio-Caroline-Volume-1/018_Maus-And-Stolle-Adore.mp3');
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
