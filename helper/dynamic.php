<?php
namespace HELPER;

class Dynamic {

    public static function instance(string $name, string $type, array $args = []) {
       
        $path = APP_ROOT . "/" . strtolower($type) . "/" . strtolower($name) . ucfirst(strtolower($type)) . ".php";
        
        if(!file_exists($path)) {
            throw new \Exception("Class does not exists");
        }

        $instanceName = "\\" . strtoupper($type) . "\\" . ucfirst(strtolower($name)) . ucfirst(strtolower($type));

        $reflection = new \ReflectionClass($instanceName);

        $instance = $reflection->newInstanceArgs($args);

        return $instance;
    }

    public static function call(object $instance, string $method, array $args = []) {
        
        $reflectionName = get_class($instance);

        $reflection = new \ReflectionClass($reflectionName);
        
        if(!$reflection->hasMethod($method)) {
            throw new \Exception($method . " does not exists");
        }

        return call_user_func_array([$instance, $method], $args);
    }

}