<?php
namespace VIEW;

/**
 * Class CompanyView
 * @package VIEW
 * @author Christian Vinding Rasmussen
 * CompanyView is used for returning the company profile and also the API logs
 */
class CompanyView extends \VIEW\BASE\View {

    /**
     * An instance of the \MODEL\LogModel
     * @var \MODEL\LogModel $logModel
     */
    private $logModel;

    /**
     * An instance of the \MODEL\CompanyModel
     * @var \MODEL\CompanyModel $companyModel
     */
    private $companyModel;

    /**
     * __construct() is used for setting the \Request class and also initalizing our class' models
     * @param \Request $request
     */
    public function __construct(\Request $request){
        parent::__construct($request);
        $this->logModel = new \MODEL\LogModel();
        $this->companyModel = new \MODEL\CompanyModel();
    }

    /**
     * getAbout() is used to return the company profile
     * @return void
     */
    public function getAbout() : void {
        
        $result = $this->companyModel->getAbout();

        if(empty($result)) {

            $httpCode = 500;
            $status = false;
             
        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

    /**
     * getLogs() is used to output all logs
     * @param int $page = 1
     * @return void
     */
    public function getLogs(int $page = 1) : void {

        $result = $this->logModel->getLogs($page);

        if(empty($result)) {
        
            $httpCode = 404;
            $status = false;
        
        } else {

            $httpCode = 200;
            $status = false;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

    /**
     * getLogsByAction() is used to output all logs with specified action
     * @param string $action
     * @param int $page = 1
     * @return void
     */
    public function getLogsByAction(string $action, int $page = 1) : void { 

        $result = $this->logModel->getLogsByAction($action, $page);

        if(empty($result)) {
            
            $httpCode = 404;
            $status = false;

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

    /**
     * getLogsByUserID() is used to output the logs which were generated for an user
     * @param string $userID
     * @param int $page = 1
     * @return void
     */
    public function getLogsByUserID(string $userID, int $page = 1) : void {

        $result = $this->logModel->getLogsByUserID($userID, $page);

        if(empty($result)) {
            
            $httpCode = 404;
            $status = false;

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

    /**
     * getLogsByIP() is used to output the logs generated with the an specific IP
     * @param string $ip
     * @param int $page = 1
     * @return void
     */
    public function getLogsByIP(string $ip, int $page = 1) : void {

        $result = $this->logModel->getLogsByIP($ip, $page);

        if(empty($result)) {
            
            $httpCode = 404;
            $status = false;

        } else if(isset($result["__error"])) {

            $httpCode = 400;
            $status = false;
            $result = $result["__error"];

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

    /**
     * getLogsByDate() is used to output the logs created a specific date
     * @param string $date
     * @param int $page = 1
     * @return void
     */
    public function getLogsByDate(string $date, int $page = 1) : void {

        $result = $this->logModel->getLogsByDate($date, $page);

        if(empty($result)) {
            
            $httpCode = 404;
            $status = false;

        } else if(isset($result["__error"])) {

            $httpCode = 400;
            $status = false;
            $result = $result["__error"];

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result, "status" => $status]));
    }

}