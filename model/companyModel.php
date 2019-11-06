<?php
namespace MODEL;

class CompanyModel extends \MODEL\BASE\Model {

    public function getInformation() {
        return $this->sendGET("/company/information");
    }

    public function getLogs(int $page = 1) {
        return $this->sendGET("/company/getLogs/" . $page, false);
    }

}