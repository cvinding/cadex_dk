<?php
namespace CONTROLLER;

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
     * 
     */
    public function authenticate(string $username, string $password) : void {

        $result = $this->authModel->authenticateUser($username, $password);        

        if($result) {
            $token = $this->authModel->createToken();
        }

        exit(json_encode(["result" => ["token" => $token], "status" => "?"]));
    }

    /**
     * 
     */
    public function validate(string $token) : void {
        exit(json_encode(["result" => "LMAO", "status" => "?"]));
    }



}