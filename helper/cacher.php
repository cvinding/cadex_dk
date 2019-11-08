<?php
namespace HELPER;

class Cacher {

    public static function cache(string $endpoint, array $endpointData) : void {

        $exploded = explode("/",$endpoint);

        array_shift($exploded);

        $cachename = implode("-", $exploded) . ".json";

        $fullCachename = APP_ROOT . "/cache/" . $cachename;

        $cache = fopen($fullCachename, "w+") or die("Unable to open cache!");

        $endpointData["expires"] = time() + 600;

        fwrite($cache, json_encode($endpointData));

        fclose($cache);
    }

    public static function cacheExists(string $endpoint) {
        $exploded = explode("/",$endpoint);

        array_shift($exploded);

        $cachename = implode("-", $exploded) . ".json";

        $fullCachename = APP_ROOT . "/cache/" . $cachename;

        return file_exists($fullCachename);
    }

    public static function getCache(string $endpoint) : array {

        $exploded = explode("/",$endpoint);

        array_shift($exploded);

        $cachename = implode("-", $exploded) . ".json";

        $fullCachename = APP_ROOT . "/cache/" . $cachename;

        $cache = fopen($fullCachename, "r");

        $data = json_decode(fread($cache, filesize($fullCachename)),true);

        fclose($cache);

        return $data;
    }

    /*public static function getCachedImages(int $productId, string $cacheName) {

        $filename = APP_ROOT . "/images/" . $cacheName;

        $file = fopen($filename, "r") or die("Unable to open cache!");

        $fileOutput = json_decode(fread($file, filesize($filename)), true);

        fclose($file);

        //var_dump($fileOutput);die;

        return $fileOutput[$productId];
    }*/
 
}