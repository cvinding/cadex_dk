<?php
namespace VIEW;

class CompanyView extends \VIEW\BASE\View {

    private $logModel;
    private $companyModel;

    public function __construct(\Request $request){
        parent::__construct($request);
        $this->logModel = new \MODEL\LogModel();
        $this->companyModel = new \MODEL\CompanyModel();
    }

    public function getAbout() : void {
        exit(json_encode(["result" => $this->companyModel->]))
    }

    public function getLogs(int $page = 1) {

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

    public function getLogsByAction(string $action, int $page = 1) { 

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

    public function getLogsByUserID(string $userID, int $page = 1) {

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

    public function getLogsByIP(string $ip, int $page = 1) {

        $result = $this->logModel->getLogsByIP($ip, $page);

        if(empty($result)) {
            
            $httpCode = 404;
            $status = false;

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        exit(json_encode(["result" => $result]));
    }

    public function getLogsByDate(string $date, int $page = 1) {

        $result = $this->logModel->getLogsByDate($date, $page);

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

}