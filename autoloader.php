<?php

/**
 * Class Autoloader
 * @author Christian Vinding Rasmussen
 * Autoloader class is a class used for automatically requiring all the instanced classes' file paths<
 */
class Autoloader {

    /**
     * register() is the detector of which class needs to be required
     * @return void
     */
    public static function register() {
        spl_autoload_register(function ($class) {
            $file =  APP_ROOT.DIRECTORY_SEPARATOR.self::getClassPath($class.".php");
            if (file_exists($file)) {
                require $file;
            }
        });
    }

    /**
     * getClassPath() returns the path to the detected class
     * @param string $className
     * @return string
     */
    private static function getClassPath(string $className) : string {
        $explodedClass = explode(DIRECTORY_SEPARATOR, str_replace("\\", DIRECTORY_SEPARATOR, $className));

        $pathToClass = "";
        for($i = 0; $i < (sizeof($explodedClass) - 1); $i++){
            $pathToClass .= strtolower($explodedClass[$i]).DIRECTORY_SEPARATOR;

        }
        $pathToClass .= lcfirst($explodedClass[sizeof($explodedClass) - 1]);

        return $pathToClass;
    }
}