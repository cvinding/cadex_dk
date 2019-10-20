<?php

/**
 * Class Dispatcher
 * @author Christian Vinding Rasmussen
 * Dispatcher is a class for determining which view/controller to call. 
 * It uses Router to match with the Request class.
 */
class OldDispatcher {

    /**
     * @var \Router $router
     */
    private $router;

    /**
     * Dispatcher constructor.
     * @param $router
     */
    public function __construct($router) {
        $this->router = $router;
    }

    /**
     * dispatch() is used for determining which endpoint and controller to call
     * @param \Request $request
     * @throws Exception
     */
    public function dispatch($request) : void {

        /**
         * @var \ENDPOINT\BASE\Endpoint $baseEndpoint
         */
        $baseEndpoint = new \ENDPOINT\BASE\Endpoint($request);

        /**
         * @var array|bool $path
         */
        $route = $this->router->match($request);

        // If route does not exists throw 404
        if($route === false) {
            http_response_code(404);
            exit(json_encode(["message" => "Resource not found", "status" => false]));
        }

        // If session are set, check if it is the same as global $_SESSION
        if(!empty($route["session"])) {

            // Check each session in session $route array
            foreach($route["session"] as $name => $value) {

                // If global $_SESSION are not equal to $route session $value redirect to a default page
                if(\SESSION\Session::get($name) !== $value) {
                    header("location: {$this->router->getDefaultRoute()["path"]}");
                }
            }
        }

        $endpointName = $this->getClassName($route["class"], "Endpoint");

        if($endpointName === false) {
            http_response_code(404);
            exit(json_encode(["message" => "Resource not found", "status" => false]));
        }

        $controllerName = $this->getClassName($route["class"], "Controller");

        if($controllerName !== false) {

            $controller = new $controllerName;

            try {

                $reflection = new ReflectionClass($controllerName);

                $hasMethod = $reflection->hasMethod($request->getAction());

            } catch (Exception $exception) {
                exit($exception);
            }

            if($request->getAction() !== false && $hasMethod) {
                $controller->{$request->getAction()}(array_merge($_POST, $request->getArgs()));
            }

        }

        /**
         * @var \VIEW\IMPLEMENT\View $view
         */
    /*    $view = new $viewName($request);

        $view->index();
*/

        $endpoint = new $endpointName($request);
        //$endpoint->{$request->getAction()}();

        call_user_func_array([$endpoint, $request->getAction()], $request->getArgs());

    }

    /**
     * getClassName() is used for finding a class, and returns the className on success or false on failure
     * @param string $name
     * @param string $classType
     * @return bool|string
     */
    private function getClassName(string $name, string $classType) {

        // Basename
        $baseName = $name.$classType;

        // Classname
        $className = ucfirst($baseName);

        // Full filepath to the class file
        $filePath = APP_ROOT."/".strtolower($classType)."/".$baseName.".php";

        // If file does not exists return false
        if(!file_exists($filePath)) {
            return false;
        }

        // Returns the full class namespace name
        return "\\".strtoupper($classType)."\\".$className;
    }

}