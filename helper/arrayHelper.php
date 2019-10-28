<?php
namespace HELPER;

class ArrayHelper {

    public static function from2DTo1D(array $dd) : array {

        $d = [];

        foreach($dd as $innerArray) {
            $d[] = $innerArray[0];
        }

        return $d;
    }

}