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
        if ($this->isYoutubeVideo($this->url)) {
            return YouTubeUtil::getInstance($this->url);
        }  else if ($this->isVimeoVideo($this->url)) {
            return VimeoUtil::getInstance($this->url);
        }

        throw new Exception('Unsupported video url.');
    }   


    // public function getVideoEmbedUrl($videoId = '')
    // {
    //     try {
    //         if (!empty($videoId)) {
    //             $src = '';

    //             if ($this->isYoutubeVideo($url)) {
    //                 $src = 'http://www.youtube.com/embed/'.$videoId;
    //             } else if ($this->isVimeoVideo($url)) {
    //                 $src = 'http://player.vimeo.com/video/'.$videoId;
    //             }

    //             return $src;
    //         }

    //         throw new \Exception("Can't retrieve embed url");
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }

    // public function getVideoEmbedTag($videoId, $width = 0, $height = 0)
    // {
    //     try {
    //         if (!empty($videoId)) {
    //             $src = $this->getVideoEmbedUrl($videoId);
    //             $attr = '';

    //             if ($this->isVimeoVideo($url)) {
    //                 $attr = 'webkitAllowFullScreen mozallowfullscreen ';
    //             }

    //             return '<iframe src="'.$src.'" width="'.$width.'" height="'.$height.'" frameborder="0" '.$attr.'allowFullScreen></iframe>';
    //         }

    //         throw new \Exception("Can't retrieve embed tag");
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }

    // public function getVideoThumbnail($videoId)
    // {
    //     try {
    //         if (!empty($videoId)) {
    //             if ($this->isYoutubeVideo($url)) {
    //                 return 'http://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg';
    //             } else if ($this->isVimeoVideo($url)) {
    //                 $data = json_decode(file_get_contents('http://vimeo.com/api/v2/video/'.$videoId.'.json'));
    //                 return $data[0]->thumbnail_large;
    //             }
    //         }

    //         throw new \Exception("Can't retrieve thumbnail");
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }

    // public function getVideoId($url = '')
    // {
    //     if (!empty($url)) {
    //         if ($this->isYoutubeVideo($url)) {
    //             $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    //             preg_match($pattern, $url, $matches);

    //             return isset($matches[1]) ? $matches[1] : '';
    //         } else if ($this->isVimeoVideo($url)) {
    //             $pattern = '/^http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/';
    //             preg_match($pattern, $url, $matches);

    //             return isset($matches[3]) ? $matches[3] : '';
    //         }

    //         throw new \Exception('URL is not supported.');
    //     }

    //     return '';
    // }

    private function isYoutubeVideo($url)
    {
        return strpos($url, 'youtu.be') > -1 || strpos($url, 'youtube') > -1;
    }

    private function isVimeoVideo($url)
    {
        return strpos($url, 'vimeo') > -1;
    }
}