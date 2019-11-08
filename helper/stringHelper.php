<?php
namespace HELPER;

/**
 * Class StringHelper
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * TODO DESc
 */
class StringHelper {

    /**
     * strsho() is a method for shortening strings. It requires a maxWordCount and returns the string with the desired length
     * @param $string
     * @param $maxWordCount
     * @param string $ending
     * @return string
     */
    public static function strsho($string, $maxWordCount, $ending = "...") {

        $specialCharacters = ["!","?",".",","];

        $explodedString = explode(" ", $string);

        $newString = "";
        foreach($explodedString as $index => $word) {

            // break the string if we have reached maxWordCount, also append ending to string
            if($index + 1 > $maxWordCount) {
                $newString = rtrim($newString) . $ending;
                break;
            }

            // If there is a !?,. then make sure it gets into the string
            if(preg_match("/[!?.,]/", $word, $match) === 1) {

                $explodedWord = preg_split("/[!?.,]/", $word);
                $newString .= $explodedWord[0].$match[0]." ".$explodedWord[1]." ";

            } else {
                // Append word to string
                $newString .= $word." ";
            }

        }

        // rtrim for whitespace
        $newString = rtrim($newString);

        return $newString;
    }

    /**
     * previewString() is a method for creating a preview string out of a text, where there could be HTML tags
     * @param $string
     * @param $maxWordCount
     * @param string $end
     * @return string
     */
    public static function previewString($string, $maxWordCount, $end = "...") {
        return self::strsho(preg_replace('/<[^>]*>/', '', $string), $maxWordCount, $end);
    }
}