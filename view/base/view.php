<?php
namespace VIEW\BASE;

/**
 * Class View
 * @package VIEW\BASE
 * @author Christian Vinding Rasmussen
 * TODO description
 */
class View {

    /**
     * An instance of the Request class
     * @var \Request $request
     */
    protected $request;

    /**
     * An array of http codes and what to output in the "status" response field
     * @var array $statusTranslations
     */
    protected $statusTranslations = [
        200 => true,
        201 => true,
        204 => true,
        400 => false,
        401 => false,
        403 => false,
        404 => false,
        500 => false
    ];

    /**
     * __construct() is used to set the Request class
     * @param \Request $request
     */
    protected function __construct(\Request $request) {
        $this->request = $request;
    }

    /**
     * __call() is used to automatically output the endpoint controller output
     * @param string $name
     * @param array $parameters
     */
    public function __call(string $name, array $parameters) {

        // Get messages
        $messages = \HELPER\MessageHandler::getMessages();

        // If no message has been set return 500 + "No message" message
        if(!isset($messages[0])) {
            http_response_code(500);
            
            exit(json_encode(["result" => "No message", "status" => false]));
        }

        // Output the message
        $message = $messages[0];

        http_response_code($message["httpCode"]);
    
        exit($this->createJSONResponse($message["message"], $message["httpCode"]));
    }

    /**
     * createJSONResponse() is used to create a simple API JSON response
     * @param string $message
     * @param int $httpCode
     * @param string
     */
    protected function createJSONResponse(string $message, int $httpCode) : string {
        $status = $this->statusTranslations[$httpCode];

        return json_encode(["result" => $message, "status" => $status]);
    }

}