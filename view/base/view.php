<?php
namespace VIEW\BASE;

class View {

    protected $request;

    protected function __construct(\Request $request) {
        $this->request = $request;
    }

    public function __call(string $name, array $parameters) {

        $messages = \HELPER\MessageHandler::getMessages();

        if(!isset($messages[0])) {
            http_response_code(500);
            
            exit(json_encode(["result" => "No message", "status" => false]));
        }

        $message = $messages[0];

        http_response_code($message["httpCode"]);
    
        exit($this->createJSONResponse($message["message"], $message["httpCode"]));
    }

    protected function createJSONResponse(string $message, int $httpCode) {
        $statusTranslations = [
            "POST" => [201 => true, 500 => false],
            "GET" => [200 => true, 404 => false],
            "PUT" => [200 => true, 204 => true, 404 => false],
            "DELETE" => [200 => true, 404 => false]
        ];

        $statusTranslation = $statusTranslations[$this->request->requestMethod];

        return json_encode(["result" => $message, "status" => $statusTranslation[$httpCode]]);
    }

}