<?php
// Set Access-Control-Allow-Methods to POST, GET, PUT, DELETE (CRUD)
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');

/**
 * Class Router
 * @author Christian Vinding Rasmussen
 * Router is used for adding and matching routes with requests.
 */
class OldRouter {

    /**
     * An array for all the available routes
     * @var array $paths
     */
    private static $paths = [
        "DELETE" => [],
        "GET" => [],
        "POST" => [],
        "PUT" => []
    ];

    /**
     * The array which stores the default route from the config file
     * @var array
     */
    private static $defaultRoute;

    /**
     * Router constructor.
     * Used for settings default and other routes from the config file
     * @throws Exception
     */
    public function __construct() {

        try {
            // Load the route config
            $config = \HELPER\ConfigLoader::load("config/route.php", ["DEFAULT"]);

        } catch (\Exception $e) {
            exit($e);
        }

        // Set $defaultRoute to default in config file
        self::$defaultRoute = $config["DEFAULT"];

        // Add every route from config file
        foreach($config as $value) {
            self::add($value["method"], $value["path"], (isset($value["class"])) ? $value["class"] : false, (isset($value["session"])) ? $value["session"] : []);
        }

    }

    /**
     * add() is used to add routes, and uses addRoute() method for adding routes to self::$paths
     * @param string|array $method
     * @param string $path
     * @param bool $class
     * @param array $session
     * @return bool
     * @throws Exception
     */
    public static function add($method, $path, $class = false, $session = []) {

        // If $class are not set, find the default class from $path variable
        if($class === false) {

            // If $path is '/' then default it to '/index'
            if($path === "/") {
                $path = "/index";
            }

            // Set $class equal to $path and remove '/'
            $class = explode("/", $path)[1];
        }

        $explodedRegex = preg_split("|(?<!\\\)/|", $path);

        $regex = "^".implode("\/", $explodedRegex)."$";

        // Create the route array
        $array = ["class" => $class, "path" => $path, "regex" => $regex, "session" => $session];

        $explodedPath = explode("/", $path);

        // If request method is an array, insert the routes into $this->paths with a foreach loop, else insert the route as a single array entry
        if(is_array($method)) {

            foreach($method as $key) {

                self::addRoute($key, $class,isset($explodedPath[2]) ? $explodedPath[2] : false, $array);
            }

        } else {

            self::addRoute($method, $class,isset($explodedPath[2]) ? $explodedPath[2] : false, $array);
        }

        return true;
    }

    /**
     * match() is used to lookup in the $this->paths array, returns the route if it exists and false if not
     * @param \Request $request
     * @return bool
     */
    public function match($request) {

        // Get full request path
        $fullPath = $request->getFullPath();

        // Get first part of $paths array
        $route = &self::$paths[$request->getMethod()][$request->getClass()];

        // If request action is not false
        if($request->getAction() !== false) {

            // Set $fullRoutes equal to action array, if request action is set in $route array
            if(isset($route[$request->getAction()])) {

                $fullRoutes = $route[$request->getAction()];

            } else {

                $fullRoutes = $route;
            }

        } else {

            $fullRoutes = $route;
        }

        // Return false if fullRoutes is NULL
        if($fullRoutes === NULL) {
            return false;
        }

        $validPath = false;

        // Foreach route in fullRoute, check if fullPath is valid
        foreach($fullRoutes as $fullRoute) {

            // Check if fullRoute is not default route
            if(!isset($fullRoute["regex"])) {

                // Loop through each action in route
                foreach($fullRoute as $fRoute) {

                    // Check if fullPath matches regex
                    if(preg_match("/".$fRoute["regex"]."/", $fullPath) === 1) {
                        $validPath = $fRoute;
                        break;
                    }
                }

            } else {

                // Check if fullPath matches regex
                if(preg_match("/".$fullRoute["regex"]."/", $fullPath) === 1) {
                    $validPath = $fullRoute;
                    break;
                }
            }

        }

        // Return path if set, else false
        return ($validPath !== false) ? $validPath : false;
    }

    /**
     * addRoute() is a method for adding routes after calling add(), it validates and checks everything for us in the $paths array
     * @param string $method
     * @param string $class
     * @param string|bool $action
     * @param array $path
     * @throws Exception
     */
    private static function addRoute($method, $class, $action, $path) {

        // Set method to upper
        $method = strtoupper($method);

        // If requested method does not exists throw exception
        if(!array_key_exists($method, self::$paths)) {
            throw new Exception("Request method '{$method}' is not allowed to be configured in \\Router class");
        }

        // If class array in $paths is not set, set it to an empty array
        if(!isset(self::$paths[$method][$class])) {
            self::$paths[$method][$class] = [];
        }

        // If action array is not set in class array and action is not false, set it to an empty array
        if(!isset(self::$paths[$method][$class][$action]) && $action !== false) {
            self::$paths[$method][$class][$action] = [];
        }

        // Set path into action array if it exists, else if action is not set then set it to default path
        if ($action !== false) {

            self::$paths[$method][$class][$action][] = $path;

        } else {

            self::$paths[$method][$class][0] = $path;
        }

    }

    /**
     * getDefaultRoute() return the default route
     * @return array
     */
    public static function getDefaultRoute() {
        return self::$defaultRoute;
    }
}