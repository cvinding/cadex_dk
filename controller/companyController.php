<?php
namespace CONTROLLER;

/**
 * Class CompanyController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The controller for user input on the Company endpoint
 */
class CompanyController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of the CompanyModel
     * @var \MODEL\CompanyModel $companyModel
     */
    private $companyModel;

    /**
     * __construct() is used for setting the \Request class and initializing the \MODEL\CompanyModel
     * @param \Request $request
     */
    public function __construct(\Request $request){
        parent::__construct($request);
        $this->companyModel = new \MODEL\CompanyModel();
    }

    /**
     * editAbout() is used to call the model for editing the company profile
     * @param string $title
     * @param string $content
     * @param string $email
     * @param string $phoneNumber
     * @return void
     */
    public function editAbout(string $title, string $content, string $email, string $phoneNumber) : void {
        
        // Try and update the profile
        $result = $this->companyModel->editAbout($title, $content, $email, $phoneNumber);
        
        // Output message based on $result
        if($result) {

            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 25);

            \HELPER\MessageHandler::attachMessage("You have successfully edited the company profile.", 200);

        } else {

            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 26);

            \HELPER\MessageHandler::attachMessage("An error occurred while trying to edit the company profile.", 500);
        }
    }

}