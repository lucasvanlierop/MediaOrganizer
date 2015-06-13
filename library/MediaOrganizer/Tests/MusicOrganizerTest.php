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
            'directories' => [
                'Rock' => [
                    'rock'
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
    public function shouldVisitRootDirectoryAndRenameSingleTrackWithoutGenre()
    {
        $config = [
            'directories' => [
                'Rock' => [
                    'rock'
                ]
            ]
        ];

        // @todo: get rid of filesystem tests when code is refactored
        $sourceFile = $this->resourcesDir . '/source-media/single-track-without-genre.mp3';
        $targetFile = $this->targetDir . '/single-track-without-genre.mp3';
        $this->createTargetDir($this->targetDir);
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileExists($this->targetDir . '/Peter-Shilling/Major-Tom.mp3');
        $this->assertFileNotExists($this->targetDir . '/single-track-without-genre.mp3');
    }

    /**
     * @test
     */
    public function shouldVisitRootDirectoryAndKeepTrackWithoutGenreInDirIfThatDirIsKnown()
    {
        $config = [
            'directories' => [
                'Rock' => [
                    'rock'
                ]
            ]
        ];

        // @todo: get rid of filesystem tests when code is refactored
        $sourceFile = $this->resourcesDir . '/source-media/single-track-without-genre.mp3';
        $targetFile = $this->targetDir . '/Rock/single-track-without-genre.mp3';
        $this->createTargetDir($this->targetDir);
        mkdir($this->targetDir . '/Rock');
        copy($sourceFile, $targetFile);

        $organizer = new MusicOrganizer($config);
        $organizer->run(__DIR__ . '/Resources/target-media');
        $this->assertFileExists($this->targetDir . '/Rock/Peter-Shilling/Major-Tom.mp3');
        $this->assertFileNotExists($this->targetDir . '/Rock/single-track-without-genre.mp3');
    }

    /**
     * @test
     */
    public function shouldVisitRootDirectoryAndRenameAlbumTrack()
    {
        $config = [
            'directories' => [
                'Dance/Div' => [
                    'ambient'
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
            'directories' => [
                'Dance/Electro' => [
                    'electro'
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
