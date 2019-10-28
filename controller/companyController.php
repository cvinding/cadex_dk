<?php
namespace CONTROLLER;

class CompanyController extends \CONTROLLER\BASE\Controller {

    private $logModel;
    private $companyModel;

    public function __construct(\Request $request){
        parent::__construct($request);
        $this->logModel = new \MODEL\LogModel();
        $this->companyModel = new \MODEL\CompanyModel();
    }

 


}