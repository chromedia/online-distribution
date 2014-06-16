<?php

/**
 * Provides helper methods for strng manipuation
 */
class StringUtil
{
    /**
     * Truncates string
     */
    public static function truncateString($string, $limit, $delimiter = " ", $pad = "...")
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