<?php


class VimeoUtil
{
    private static $instance;

    private $url = '';
    
    /**
     * Returns instance
     */
    public static function getInstance($url)
    {
        if (is_null(self::$instance)) {
            self::$instance = new VimeoUtil($url);
        }

        return self::$instance;
    }

    /**
     * Instatiates video utility
     */
    public function __construct($url)
    {
        $this->url = $url;
    }


    public function getVideoEmbedUrl($videoId = '')
    {
        try {
            if (!empty($videoId)) {
                return 'http://player.vimeo.com/video/'.$videoId;
            }

            throw new \Exception("Can't retrieve embed url");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVideoEmbedTag($videoId, $width = 0, $height = 0)
    {
        try {
            if (!empty($videoId)) {
                $src = $this->getVideoEmbedUrl($videoId);

                return '<iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen  allowFullScreen></iframe>';
            }

            throw new \Exception("Can't retrieve embed tag");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVideoThumbnail($videoId)
    {
        try {
            if (!empty($videoId)) {
                $data = json_decode(file_get_contents('http://vimeo.com/api/v2/video/'.$videoId.'.json'));
                
                return $data[0]->thumbnail_large;
            }

            throw new \Exception("Can't retrieve thumbnail");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVideoId()
    {
        if (!empty($this->url)) {
            $pattern = '/^http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/';
            preg_match($pattern, $this->url, $matches);

            return isset($matches[3]) ? $matches[3] : '';
        }

        return '';
    }
}