<?php

require_once(DIR_SYSTEM . 'utilities/YouTubeUtil.php');

require_once(DIR_SYSTEM . 'utilities/VimeoUtil.php');



class VideoUtilTypeFactory
{

    private static $instance;

    private $url = '';
    
    /**
     * Returns instance
     */
    public static function getInstance($videoUrl)
    {
        if (is_null(self::$instance)) {
            self::$instance = new VideoUtilTypeFactory($videoUrl);
        }

        return self::$instance;
    }

    /**
     * Instatiates video utility
     */
    public function __construct($videoUrl)
    {
        $this->url = $videoUrl;
    }


    /**
     * Returns video utility
     */
    public function getVideoUtility()
    {
        $this->addHttps();

        if ($this->isYoutubeVideo($this->url)) {
            return YouTubeUtil::getInstance($this->url);
        }  else if ($this->isVimeoVideo($this->url)) {
            return VimeoUtil::getInstance($this->url);
        }

        throw new Exception('Unsupported video url format.');
    }

    private function isYoutubeVideo($url)
    {
        return strpos($url, 'youtu.be') > -1 || strpos($url, 'youtube') > -1;
    }

    private function isVimeoVideo($url)
    {
        return strpos($url, 'vimeo') > -1;
    }

    private function addHttps()
    {
        $this->url = preg_replace('#^https?://#', '', rtrim($this->url,'/'));
        $this->url = 'https://'.$this->url;
    }
} 