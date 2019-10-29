<?php declare(strict_types=1);
namespace PUBLIC_HTML;

//TODO: used for debugging
ini_set('display_errors', "On");
ini_set('display_startup_errors', "On");
error_reporting(E_ALL);

// Define APP_ROOT
$DOCUMENT_ROOT = explode("/", $_SERVER["DOCUMENT_ROOT"]);
array_pop($DOCUMENT_ROOT);
define("APP_ROOT", implode("/", $DOCUMENT_ROOT));

// Require the autoloader class
require_once APP_ROOT."/autoloader.php";

// Start the Autoloader
\Autoloader::register();

try {

    // Start the session
    \SESSION\Session::start();

    // Initialize the Request class
    $request = new \Request();

    // Initialize the Router class
    $router = new \Router();

    // Require the predefined routes
    require_once APP_ROOT."/routes.php";

    // Initialize the Dispatcher class
    $dispatcher = new \Dispatcher($router);

    // Find our destination
    $dispatcher->dispatch($request);

}  catch (\Exception $exception) {
    exit($exception);
} 