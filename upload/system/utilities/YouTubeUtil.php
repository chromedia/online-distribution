<?php


class YouTubeUtil
{
    private static $instance;

    private $url = '';
    
    /**
     * Returns instance
     */
    public static function getInstance($url)
    {
        if (is_null(self::$instance)) {
            self::$instance = new YouTubeUtil($url);
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
                return 'https://www.youtube.com/embed/'.$videoId;
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
                return '<iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowFullScreen></iframe>';
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
                return 'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg';
            }

            throw new \Exception("Can't retrieve thumbnail");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getVideoId()
    {
        if (!empty($this->url)) {
            $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
            preg_match($pattern, $this->url, $matches);

            return isset($matches[1]) ? $matches[1] : '';
        }

        return '';
    }
}