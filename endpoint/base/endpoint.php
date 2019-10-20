<?php
namespace ENDPOINT\BASE;

/**
 * Class Endpoint
 * @package ENDPOINT\BASE
 * @author Christian Vinding Rasmussen
 * 
 */
class Endpoint {

    /**
     * @var \Request $request
     */
    protected $request;

    public function __construct(\Request $request) {
        $this->request = $request;
    }



}