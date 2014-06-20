<?php

/**
 * Provides helper methods for strng manipuation
 */
class StringUtil
{
    private static $instance;
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new StringUtil();
        }

        return self::$instance;
    }

    /**
     * Truncates string
     */
    public function truncateString($string, $limit, $delimiter = " ", $pad = "...")
    {
        if (strlen($string) <= $limit) {
            return $string;
        } else if (0 == $limit) {
            return '';
        }

        if(false !== ($breakpoint = strpos($string, $delimiter, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) .$pad;
            }
        }

        return $string;
    }
}