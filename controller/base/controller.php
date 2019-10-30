<?php
namespace CONTROLLER\BASE;

/**
 * Class Controller
 * @package CONTROLLER\BASE
 * @author Christian Vinding Rasmussen
 * Controller is the Base Class of all controllers.
 */
class Controller {

    /**
     * An instance of the Request class
     * @var \Request $request
     */
    protected $request;

    /**
     * The username from the token claims
     * @var string $username
     */
    protected $username = null;

    /**
     * __construct() is used for setting the Request class and setting the uid token claim, if a token isset
     */
    public function __construct(\Request $request) {
        $this->request = $request;

        // Get and set username from token, if token is not false
        if($this->request->token !== false) {
            $this->username = (new \MODEL\AuthModel())->getTokenClaim($this->request->token, "uid");
        }
    }

}