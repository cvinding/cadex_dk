<?php

/**
 * class Dispatcher
 * @author Christian Vinding Rasmussen
 * TODO description
 */
class Dispatcher {

    /**
     * An instance of the Router class
     * @var Router $router
     */
    private $router;

    /**
     * __construct() is used to create an instance of the Router class
     * @param Router $router
     */
    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * dispatch() is a method for checking the requested route exists, and that the correct handler gets called
     * @param Request $request
     * @return void
     */
    public function dispatch(Request $request) : void {

        // Get the request handler
        $handler = $this->router->match($request);

        // If $handler is false give a standard not found 404 message
        if($handler === false) {
            http_response_code(404);
            exit(json_encode(["result" => "Resource not found!", "status" => false]));
        }

        // If $handler is callable then call and exit the code
        if(is_callable($handler)) {
            exit($handler($request));
        }

        // Split the class name & action name
        $explodedHandler = explode("@", $handler);

        $classToCall = $explodedHandler[0];
        $actionToCall = $explodedHandler[1];

        // Return an array with the finished namespace
        $classes = $this->finishNamespace($classToCall);

        // If CONTROLLER is set then check if action exists in the controller
        if(isset($classes["CONTROLLER"])) {

            // Create controller reflection
            $reflection = new \ReflectionClass($classes["CONTROLLER"]);                

            // Check if $reflection instance has the $actionToCall method and call it if so
            if($reflection->hasMethod($actionToCall)) {
                call_user_func_array([new $classes["CONTROLLER"](), $actionToCall], array_merge($request->uriArgs, $request->getBody()));
            }
        }

        // If VIEW is set then check if action exists in the view
        if(isset($classes["VIEW"])) {

            // Create view reflection
            $reflection = new \ReflectionClass($classes["VIEW"]);
        
            // Check if $reflection instance has the $actionToCall method and call it if so
            if($reflection->hasMethod($actionToCall) || $reflection->isSubclassOf("\\VIEW\\BASE\\View")) {
                call_user_func_array([new $classes["VIEW"]($request), $actionToCall], $request->uriArgs);
            }
        }

    }

    /**
     * finishNamespace() is a method for determening what namespace the class has.
     * @param string $baseClass
     * @return array
     */
    private function finishNamespace(string $baseClass) : array {

        $returnable = [];

        // Split class name, so ExampleView becomes ["Example","View"];
        $classParts = preg_split('/(?=[A-Z])/', $baseClass);

        // If first entry is empty shift the array
        if(isset($classParts[0]) && $classParts[0] === ""){
            array_shift($classParts);
        }

        // If $classParts[1], e.g. View or Controller, is not empty string then create a namespace for them
        if(isset($classParts[1]) && $classParts[1] !== "") {

            $returnable[strtoupper($classParts[1])] = "\\" . strtoupper($classParts[1]) . "\\" . $baseClass;

        // Else create a namespace for both types of classes
        } else {

            $returnable["CONTROLLER"] = "\\CONTROLLER\\" . $baseClass . "Controller";
            $returnable["VIEW"] = "\\VIEW\\" . $baseClass . "View";
        }

        // Return the finished array with namespaces
        return $returnable;
    }

}