<?php
namespace CONTROLLER\BASE;

/**
 * Class Controller
 * @package CONTROLLER\BASE
 * @author Christian Vinding Rasmussen
 * Controller is the Base Class of all controllers.
 */
class Controller {

    protected $request;

    public function __construct(\Request $request) {
        $this->request = $request;
    }

}