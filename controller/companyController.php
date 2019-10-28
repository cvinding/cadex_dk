<?php
namespace CONTROLLER;

class CompanyController extends \CONTROLLER\BASE\Controller {

    private $companyModel;

    public function __construct(\Request $request){
        parent::__construct($request);
        $this->companyModel = new \MODEL\CompanyModel();
    }

    public function editAbout(string $title, string $content, string $email, string $phoneNumber) : void {
        $result = $this->companyModel->editAbout($title, $content, $email, $phoneNumber);
        
        if($result)
    }


}