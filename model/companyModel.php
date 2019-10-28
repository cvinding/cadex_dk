<?php
namespace MODEL;

class CompanyModel extends \MODEL\BASE\Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAbout() : array {
        return $this->database->query("SELECT ci.id, ci.title, ci.content, ci.email, ci.phone_number, u.username author FROM company_information ci INNER JOIN users u ON ci.author = u.id")->fetchAssoc();
    }

    public function editAbout(string $title, string $content, string $email, string $phoneNumber) : bool {
        
        $this->database->query("UPDATE company_information SET title = :title, content = :content, email = :email, phone_number = :phoneNumber WHERE id = 1", ["title" => $title, "content" => $content, "email" => $email, "phoneNumber" => $phoneNumber])->affectedRows();

        return true;
    }

}