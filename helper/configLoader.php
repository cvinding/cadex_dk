<?php
namespace HELPER;

/**
 * Class ConfigLoader
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * ConfigLoader is unsurprisingly used for loading config files, from the config/ directory
 */
class ConfigLoader {

    /**
     * load() is used to load config files from the config/ directory
     * @param string $file
     * @param array $requiredKeys
     * @return array
     * @throws \Exception
     */
    public static function load(string $file, array $requiredKeys = []) : array {

        // Create full path to config file
        $fullPath = APP_ROOT."/{$file}";

        $int = array_search("config", ($explodedPath = explode("/", $fullPath)));

        $name = "";

        for($i = $int; $i < sizeof($explodedPath); $i++) {
            $name .= $explodedPath[$i]."/";
        }

        $name = rtrim($name, "/");

        // Check if full path config file exists
        if(!file_exists($fullPath)) {

            // Check if file exists
            if(!file_exists($file)) {
                throw new \Exception("Missing '{$name}' config file");
            }

        }else {
            // Set file equal to full path
            $file = $fullPath;
        }

        // Get the config
        $config = require $file;

        // Loop through each required key and check if any required key is missing from the config
        foreach($requiredKeys as $key) {

            if(!isset($config[$key])) {
                throw new \Exception("Missing required key '{$key}' in '{$name}'");
            }
        }

        return $config;
    }

}