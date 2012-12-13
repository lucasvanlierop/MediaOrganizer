<?php
class MusicOrganizer_MetaData
{
    private $_info;

    public function __construct($fileName) {
        $id3 = $this->_getId3Instance();

      //  echo PHP_EOL . 'FN: ' . $fileName;
        $this->_info = $id3->analyze($fileName);
        getid3_lib::CopyTagsToComments($this->_info);

        //var_dump($this->_info);die;

        if (isset($this->info['comments'])) {
            printvar_dump($this->_info['comments']);die;
        }
    }

    protected function _getId3Instance()
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

            $id3Instance = new getID3;
            $id3Instance->setOption('md5_data', true);
     //       $id3Instance->setOption('md5_data_source', true); // where is this for?
    		$id3Instance->setOption('encoding', 'UTF-8');
        }

        return $id3Instance;
    }

    public function getHash() {
        $id3 = $this->_getId3Instance();
        $id3->getHashData('md5');
        $hash = $id3->info['md5_data'];
        return $hash;
    }

    public function mapGenre($s_genre)
    {
        switch (true) {
            case preg_match('/alt|emo|wave|hippie|indie|rock/', $s_genre) :
                $s_genre = 'rock';
                break;
            case preg_match('/blues/', $s_genre) :
                $s_genre = 'blues';
                break;
            case preg_match('/country/', $s_genre) :
                $s_genre = 'country';
                break;
            case preg_match('/grunge|heavy|punk|metal/', $s_genre) :
                $s_genre = 'rock_hard';
                break;
            case preg_match('/reggae/', $s_genre) :
                $s_genre = 'reggae';
                break;
            case preg_match('/singer|songwriter|solo/', $s_genre) :
                $s_genre = 'singer_songwriter';
                break;
            case preg_match('/dance|house|rave|techno|club|electro/', $s_genre) :
                $s_genre = 'dance';
                break;
            case preg_match('/pop|top/', $s_genre) :
                $s_genre = 'pop';
                break;
            default :
                $s_genre = 'div';
                break;
        }
        return $s_genre;
    }

    public function guessIsCompilation($albumTitle)
    {

    }

    public function buildArtist()
    {
        if (empty($this->_info['artist'])) {
            return;
        }

        $s_artist = $this->_info['artist'][0];
        // @todo move this to rename filter
        if (substr($s_artist, 0, 4) == 'The-') $s_artist = substr($s_artist, 4) . '_(the)';
        return $s_artist;
    }

    public function buildTitle() {
        if (empty($this->_info['title'])) {
            return;
        }

        $s_title = $this->_info['title'][0];
        return $s_title;
    }

    public function buildGenre() {
        if (empty($this->_info['genre'])) {
            return;
        }

        // Todo check clean name for genre
        $s_genre = $this->_info['genre'][0];
        return $s_genre;
    }

    public function buildAlbum()
    {
        if (empty($this->_info['album'])) {
            return;
        }

        $s_album = '';
        if (!empty($this->_info['album'][0])) {
            $s_album = $this->_info['album'][0];
            // @todo move this to rename filter
            if (!empty($this->_info['year'][0]) && preg_match('/\d{4}/', $this->_info['year'][0])) {
                $s_album = $this->_info['year'][0] . '-' . $s_album;
            }
        }

        return $s_album;
    }

    public function buildTrackNr()
    {
        if (empty($this->_info['track_number'])) {
            return;
        }

        $i_track = !isset($this->_info['track_number']) ? ''
            : intval(trim(reset($this->_info['track_number'])));
        $s_track = str_pad($i_track, 3, '0', STR_PAD_LEFT);
        return $s_track;
    }


    /**
     * @todo improve this
     */
    public function getComments() {


        return $this->_info;
    }

}