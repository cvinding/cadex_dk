<?php
namespace SESSION;

// Start all sessions
session_start();

/**
 * Class Session
 * @package SESSION
 */
class Session {

    private static $start = false;

    /**
     * start() is used to set default $_SESSION variables
     * @return bool
     * @throws \Exception
     */
    public static function start() : bool {
        
        // If start has been run, throw exception
        if(Session::$start) {
            throw new \Exception("Session::start() has already been called");
        }
        
        // Set start to true, to indicate that this function has been run
        Session::$start = true;
        
        try {
            // Load the session config
            $config = \HELPER\ConfigLoader::load("config/session.php");
        } catch (\Exception $e) {
            exit($e);
        }
        
        // Foreach config entry as $path => $value
        foreach($config as $path => $value){
            // If the session has not been set, set it to its default value
            if(Session::get($path) === false){
                Session::set($path, $value);
            }
        }

        return true;
    }

    /**
     * @param string $path
     * @return bool|mixed
     * @throws \Exception
     */
    public static function get(string $path) {
    
        // Check if Session::start() has been called
        if(!Session::$start) {
            throw new \Exception("Session::start has not been called");
        }

        // Get the $_SESSION variable as a reference
        $session = &$_SESSION;
        
        // Explode on '/' and get all keys for the $_SESSION
        $keys = explode("/", $path);
        
        // Get the $_SESSION values with each $key
        foreach($keys as $key) {
            
            if($key === ""){
                continue;
            }
            
            if(is_object($session)){
                Throw new \Exception("Invalid path configured");
            }
        
            $session = &$session[$key];
        
        }
        
        // Return the $session result or false if there is none
        return (isset($session)) ? $session : false;
    }
    
    /**
     * @param string $path
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public static function set(string $path, $value) : bool {
    
        // Check if Session::start() has been called
        if(!Session::$start) {
            throw new \Exception("Session::start has not been called");
        }

        // Get the $_SESSION variable as a reference
        $sessions = &$_SESSION;
        
        // Explode on '/' and get all keys for the $_SESSION
        $keys = explode("/", $path);
        
        // Get the $_SESSION values with each $key
        foreach($keys as $key){
        
            if($key === ""){
                continue;
            }
        
            $sessions = &$sessions[$key];
        }
        
        // Set the new $_SESSION with the new value
        $sessions = $value;
        
        // Return true or false, if set or not
        return (isset($sessions));
    }
}