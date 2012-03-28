<?php
class MusicOrganizer_MetaData
{
    private $_info;

    public function __construct($fileName) {
        $id3 = $this->_getId3Instance();

      //  echo PHP_EOL . 'FN: ' . $fileName;
        $this->_info = $id3->analyze($fileName);
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

    protected function _mapGenre($s_genre)
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

    protected function _guessIsCompilation($albumTitle)
    {

    }

    protected function _buildArtist()
    {
        $s_artist = _cleanName($a_comments['artist'][0]);
        if (substr($s_artist, 0, 4) == 'The-') $s_artist = substr($s_artist, 4) . '_(the)';
        return $s_artist;
    }

    protected function _buildTitle() {
        $s_title = _cleanName($a_comments['title'][0]);
        return $s_title;
    }

    protected function _buildGenre() {
        // Todo check clean name for genre
        $s_genre = _cleanName($a_comments['genre'][0]);
        return $s_genre;
    }

    protected function _buildAlbum()
    {
        $s_album = '';
        if (!empty($a_comments['album'][0])) {
            $s_album = _cleanName($a_comments['album'][0]);
            if (!empty($a_comments['year'][0]) && preg_match('/\d{4}/', $a_comments['year'][0])) {
                $s_album = $a_comments['year'][0] . '-' . $s_album;
            }
        }

        return $s_album;
    }

    protected function _buildTrackNr()
    {
        $i_track = !isset($a_comments['track_number']) ? ''
            : intval(trim(reset($a_comments['track_number'])));
        $s_track = str_pad($i_track, 3, '0', STR_PAD_LEFT);
        return $s_track;
    }


    /**
     * @todo improve this
     */
    public function getComments() {
        
        $ThisFileInfo = $getID3->analyze($FullFileName);
        getid3_lib::CopyTagsToComments($ThisFileInfo);

        return $ThisFileInfo;
    }

}