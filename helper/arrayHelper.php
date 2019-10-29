<?php
namespace HELPER;

/**
 * Class ArrayHelper
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * A simple method for helping with arrays
 */
class ArrayHelper {

    /**
     * from2DTo1D() is a method for converting 2-dimentional arrays to 1-dimentional arrays
     * @param array $dd
     * @return array
     */
    public static function from2DTo1D(array $dd) : array {

        $d = [];

        foreach($dd as $innerArray) {
            $d[] = $innerArray[0];
        }

        return $d;
    }

}