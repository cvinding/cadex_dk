<?php
namespace CONTROLLER;

/**
 * Class AuthController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The controller for user input on the Auth endpoint.
 */
class AuthController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of the \MODEL\AuthModel
     * @var \MODEL\AuthModel $authModel
     */
    private $authModel;

    /**
     * __construct() creates a new instance of the AuthModel
     * @param \Request $request
     */
    public function __construct(\Request $request) {
        parent::__construct($request);    
        $this->authModel = new \MODEL\AuthModel();
    }

    /**
     * authenticate() is the endpoint for authenticate and generating a new token for authenticated users
     * @param string $username
     * @param string $password
     * @return void
     */
    public function authenticate(string $username, string $password) : void {

        // Authenticate the user
        $result = $this->authModel->authenticateUser($username, $password);        

        // Create token if user authenticated successfully 
        if($result) {
            
            $token = $this->authModel->createToken();

            // Log this action
            \HELPER\Logger::log($username, $this->request->remoteAddr, 5, 1);

            \HELPER\MessageHandler::attachMessage($token, 201);

        // Throw error message if not
        } else {

            // Log this action
            \HELPER\Logger::log($username, $this->request->remoteAddr, 5, 2, false);

            \HELPER\MessageHandler::attachMessage("Unable to authenticate user.", 401);
        }
    }

    /**
     * validate() is the endpoint for validating JWTs
     * @param string $token
     * @return void
     */
    public function validate(string $token) : void {
        
        // Try and validate the $token
        $isValid = $this->authModel->validateToken($token);

        // Output message based on outcome
        if($isValid) {

            // Log this action
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 5, 3);

            \HELPER\MessageHandler::attachMessage("Valid token.", 200);
        
        } else {

            // Log action
            \HELPER\Logger::log("UNKNOWN_USER", $this->request->remoteAddr, 5, 4, false);

            \HELPER\MessageHandler::attachMessage("Invalid token.", 401);
        }
    }

}