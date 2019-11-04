<?php
namespace MODEL;

class CompanyModel extends \MODEL\BASE\Model {

    public function getInformation() {
        return $this->sendGET("/company/information");
    }



}