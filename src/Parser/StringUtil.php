<?php


namespace PeterVanDommelen\Parser\Parser;


class StringUtil
{
    public static function slice($string, $position, $length = null) {
        if ($length === null) {
            $rest_of_string = substr($string, $position);
        } else {
            $rest_of_string = substr($string, $position, $length);
        }
        if ($rest_of_string === false) {
            $rest_of_string = "";
        }
        return $rest_of_string;
    }
}