<?php
namespace MODEL;

class CompanyModel extends \MODEL\BASE\Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAbout() : array {
        return $this->database->query("SELECT * FROM company_information")->fetchAssoc();
    }

}