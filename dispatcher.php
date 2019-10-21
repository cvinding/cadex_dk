<?php

class Dispatcher {

    /**
     * @var Router $router
     */
    private $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function dispatch(Request $request) : void {

        $handler = $this->router->match($request);

        if($handler === false) {
            http_response_code(404);
            exit(json_encode(["result" => "Resource not found!", "status" => false]));
        }

        if(is_callable($handler)) {
            exit($handler($request));
        }


        $explodedHandler = explode("@", $handler);

        $classToCall = $explodedHandler[0];
        $actionToCall = $explodedHandler[1];

        $classes = $this->finishNamespace($classToCall);

        if(isset($classes["CONTROLLER"])) {
            
            $reflection = new \ReflectionClass($classes["CONTROLLER"]);
                

            if($reflection->hasMethod($actionToCall)) {
                call_user_func_array([new $classes["CONTROLLER"](), $actionToCall], array_merge($request->uriArgs, $request->getBody()));
            }
        }

        if(isset($classes["VIEW"])) {

            $reflection = new \ReflectionClass($classes["VIEW"]);
        
            if($reflection->hasMethod($actionToCall) || $reflection->isSubclassOf("\\VIEW\\BASE\\View")) {
                call_user_func_array([new $classes["VIEW"]($request), $actionToCall], $request->uriArgs);
            }
        }
    }

    private function finishNamespace(string $baseClass) {

        $returnable = [];

        $classParts = preg_split('/(?=[A-Z])/', $baseClass);

        if(isset($classParts[0]) && $classParts[0] === ""){
            array_shift($classParts);
        }

        if(isset($classParts[1]) && $classParts[1] !== "") {

            $returnable[strtoupper($classParts[1])] = "\\" . strtoupper($classParts[1]) . "\\" . $baseClass;

        } else {

            $returnable["CONTROLLER"] = "\\CONTROLLER\\" . $baseClass . "Controller";
            $returnable["VIEW"] = "\\VIEW\\" . $baseClass . "View";
        }

        return $returnable;
    }

}