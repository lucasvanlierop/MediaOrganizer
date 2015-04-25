<?php
namespace MediaOrganizer\File;

/**
 * Value object for metadata
 *
 * Class MetaData
 * @package MediaOrganizer\File
 */
class MetaData
{
    /**
     * @var array
     */
    private $info;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $id3 = $this->getId3Instance();

        //  echo PHP_EOL . 'FN: ' . $fileName;
        $this->info = $id3->analyze($fileName);
        \getid3_lib::CopyTagsToComments($this->info);

//        if (isset($this->info['comments']['part_of_a_compilation'])) {
//            print_r($this->info['comments']);die;
//        }

    }

    /**
     * @return \getID3
     */
    protected function getId3Instance()
    {
        static $id3Instance;

        if (!is_object($id3Instance)) {
            require_once('vendor/getid3/getid3.php');
            /*
            $getID3->setOption(array('encoding'=>$TaggingFormat));
            getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);

            $tagwriter = new getid3_writetags;
            $tagwriter->filename       = $Filename;
            $tagwriter->tagformats     = $TagFormatsToWrite;
            $tagwriter->overwrite_tags = false;
            $tagwriter->tag_encoding   = $TaggingFormat;
            */

            $id3Instance = new \getID3;
            $id3Instance->setOption('md5_data', true);
            //       $id3Instance->setOption('md5_data_source', true); // where is this for?
            $id3Instance->setOption('encoding', 'UTF-8');
        }

        return $id3Instance;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        $id3 = $this->getId3Instance();
        $id3->getHashData('md5');
        $hash = $id3->info['md5_data'];
        return $hash;
    }

    /**
     * @param string $albumTitle
     * @return void
     */
    public function guessIsCompilation($albumTitle)
    {
    }

    /**
     * @return boolean
     */
    public function isCompilation()
    {
        if (!empty($this->info['comments']['part_of_a_compilation'])) {
            return (bool)$this->info['comments']['part_of_a_compilation'];
        }

        return false;
    }

    /**
     * @return void
     */
    public function getArtist()
    {
        if (empty($this->info['comments']['artist'])) {
            return;
        }

        $artist = $this->info['comments']['artist'][0];
        return $artist;
    }

    /**
     * @return void
     */
    public function getAlbumArtist()
    {
        if (empty($this->info['comments']['album_artist'])) {
            return;
        }

        return $this->info['comments']['album_artist'];
    }

    /**
     * @return void
     */
    public function getTitle()
    {
        if (empty($this->info['comments']['title'])) {
            return;
        }

        $title = $this->info['comments']['title'][0];
        return $title;
    }

    /**
     * @return void
     */
    public function getGenre()
    {
        if (empty($this->info['comments']['genre'])) {
            return;
        }

        // Todo check clean name for genre
        $genre = $this->info['comments']['genre'][0];
        return $genre;
    }

    /**
     * @return void
     */
    public function getAlbum()
    {
        if (empty($this->info['comments']['album'])) {
            return;
        }

        $album = '';
        if (!empty($this->info['comments']['album'][0])) {
            $album = $this->info['comments']['album'][0];
            // @todo move this to rename filter
            if (!empty($this->info['comments']['year'][0]) && preg_match('/\d{4}/', $this->info['comments']['year'][0])) {
                $album = $this->info['comments']['year'][0] . '-' . $album;
            }
        }

        return $album;
    }

    /**
     * @return void
     */
    public function getTrackNr()
    {
        if (empty($this->info['comments']['track_number'])) {
            return;
        }

        $trackNr = !isset($this->info['comments']['track_number']) ? ''
            : intval(trim(reset($this->info['comments']['track_number'])));
        $track = str_pad($trackNr, 3, '0', STR_PAD_LEFT);
        return $track;
    }

    /**
     * @todo improve this
     * @return string
     */
    public function getComments()
    {

        return $this->info['comments'];
    }
}
