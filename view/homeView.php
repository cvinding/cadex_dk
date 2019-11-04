<?php
namespace VIEW;

class HomeView extends \VIEW\BASE\View {

    public $title = "CADEX - Hjem";

    private $companyModel;

    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->companyModel = new \MODEL\CompanyModel();
    }

    public function index() {
        
        //$this->render("ui-elements/navbar.php",["login" => \SESSION\Session::get("LOGIN/STATUS")])
        exit($this->renderView("standard/standard.php", [
            "content" => $this->createInformationOutput()
        ]));
    }

    private function createInformationOutput() {
        
        $companyInformation = $this->companyModel->getInformation()["result"][0];

        $html = '<h2>' . $companyInformation["title"] . '</h2>';
        $html .= '<p>' . $companyInformation["content"] . '</p>';

        return $html;
    }

}