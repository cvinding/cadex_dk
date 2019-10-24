<?php
namespace CONTROLLER;

/**
 * Class AuthController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * TODO description
 */
class AuthController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of the \MODEL\AuthModel
     * @var \MODEL\AuthModel $authModel
     */
    private $authModel;

    /**
     * __construct() creates a new instance of the AuthModel
     */
    public function __construct() {    
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

            \HELPER\MessageHandler::attachMessage($token, 201);

        // Throw error message if not
        } else {

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

            \HELPER\MessageHandler::attachMessage("Valid token.", 200);
        
        } else {

            \HELPER\MessageHandler::attachMessage("Invalid token.", 401);
        }
    }

}