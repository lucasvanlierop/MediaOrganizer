<?php

namespace MediaOrganizer\Tests;

use MediaOrganizer\MusicOrganizer;
use PHPUnit_Framework_TestCase;

class MusicOrganizerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $resourcesDir;

    /**
     * @var string;
     */
    private $targetDir;

    public function setUp()
    {
        $this->resourcesDir = __DIR__ . '/Resources';
        $this->targetDir = $this->resourcesDir . '/target-media';
    }

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
        $sourceFile = $this->resourcesDir . '/source-media/single-track.mp3';
        $targetFile = $this->targetDir . '/single-track.mp3';
        $this->createTargetDir($this->targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileExists($this->targetDir . '/Rock/Foo-Fighters/Everlong.mp3');
        $this->assertFileNotExists($this->targetDir . '/single-track.mp3');
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
        $sourceFile = $this->resourcesDir . '/source-media/album-track.mp3';
        $targetFile = $this->targetDir . '/album-track.mp3';
        $this->createTargetDir($this->targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileExists($this->targetDir . '/Dance/Div/Enigma/1990-Mcmxc-Ad-(the-Limited-Edition)/001_Sadeness.mp3');
        $this->assertFileNotExists($this->targetDir . '/album-track.mp3');
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
        $sourceFile = $this->resourcesDir . '/source-media/compilation-track.mp3';
        $targetFile = $this->targetDir . '/compilation-track.mp3';
        $this->createTargetDir($this->targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileExists($this->targetDir . '/Dance/Electro/Miss-Kittin/2002-Radio-Caroline-Volume-1/018_Maus-And-Stolle-Adore.mp3');
        $this->assertFileNotExists($this->targetDir . '/compilation-track.mp3');
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
