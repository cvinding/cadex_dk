<?php
namespace VIEW;

/**
 * Class AuthView
 * @package VIEW
 * @author Christian Vinding Rasmussen
 * The AuthView is used for showing the generated API token
 */
class AuthView extends \VIEW\BASE\View {

    /**
     * __construct() is used to set the Request class in the base View
     * @param \Request $request
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
    }

    /**
     * authenticate() is used to format the output of the authenticate endpoint
     * @return void
     */
    public function authenticate() : void {
        $message = \HELPER\MessageHandler::getMessages()[0];

        http_response_code($message["httpCode"]);

        exit(json_encode(["result" => ["token" => $message["message"]], "status" => $this->statusTranslations[$message["httpCode"]]]));
    }

}