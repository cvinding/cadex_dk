<?php

/**
 * Class Router
 * @author Christian Vinding Rasmussen
 * Router class is the class that handles all the defined routes.
 * It is used by the Dispatcher class to find which endpoint the API should call/show.
 */
class Router {

    /**
     * @var array $validRequestMethods
     */
    private $validRequestMethods = ["POST", "GET", "PUT", "DELETE"];

    /**
     * __call() is used to handle our request methods, e.g. $router->get() or $router->put()
     * @param string $method
     * @param array $parameters
     */
    public function __call(string $method, array $parameters) {

        // If the $method used is not valid then throw exception
        if(!in_array(strtoupper($method), $this->validRequestMethods)) {
            throw new Exception("Request method '".strtoupper($method)."' is not supported!");
        }

        // Set $route & $handler
        $route = $parameters[0];
        $handler = $parameters[1];

        // Explode $route
        $explodedRoute = explode("/", $route);

        // Remove first entry in array
        array_shift($explodedRoute);

        // Set $class
        $class = ($explodedRoute[0] !== "") ? $explodedRoute[0] : "index";

        // Set $action
        $action = (isset($explodedRoute[1]) && $explodedRoute[1] !== false) ? $explodedRoute[1] : false;

        // Explode $route into $regex
        $explodedRegex = preg_split("|(?<!\\\)/|", $route);

        // Create the $regex
        $regex = "^".implode("\/", $explodedRegex)."$";

        // Set route into $method array
        $this->{strtolower($method)}[$class][$action][$regex] = $handler;
    }

    /**
     * match() is used to check if a route exists and then return string or callable on success or false on failure
     * @param Request $request
     * @return string|callable|bool
     */
    public function match(Request $request) {

        // Check if request method is valid
        if(!in_array($request->requestMethod, $this->validRequestMethods)) {
            return false;
        }

        // Get all available routes 
        $availableRoutes = $this->{strtolower($request->requestMethod)};

        // Check if class/action exists in available routes
        if(!isset($availableRoutes[$request->class]) || !isset($availableRoutes[$request->class][$request->action])) {
            return false;
        }

        // Narrow it down to all the routes we have to loop through
        $routes = $availableRoutes[$request->class][$request->action];

        // Loop through each route and check with regex if it is our route
        foreach($routes as $regex => $handler){
            
            // Return handler if regex matches request URI
            if(preg_match("/".$regex."/", $request->requestUri) === 1) {
                return $handler;
            }
        } 

        // Return false on failure
        return false;
    }

}