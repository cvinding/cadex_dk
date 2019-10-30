<?php
namespace MODEL;

/**
 * Class CompanyModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The CompanyModel for administrating the company profile
 */
class CompanyModel extends \MODEL\BASE\Model {

    /**
     * __construct() call parent::__construct() for setting the Database instance
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * getAbout() returns the company profile
     * @return array
     */
    public function getAbout() : array {
        return $this->database->query("SELECT ci.id, ci.title, ci.content, ci.email, ci.phone_number, u.username author FROM company_information ci INNER JOIN users u ON ci.author = u.id")->fetchAssoc();
    }

    /**
     * editAbout() is used for updating the company profile
     * @param string $title
     * @param string $content
     * @param string $email
     * @param string $phoneNumber
     * @return bool
     */
    public function editAbout(string $title, string $content, string $email, string $phoneNumber) : bool {
        
        // Try and get the profile
        $profile = $this->database->query("SELECT id FROM company_information LIMIT 1")->fetchAssoc();

        // Profile does not exists? return false
        if(empty($profile)) {
            return false;
        }

        // Update the entry
        $this->database->query("UPDATE company_information SET title = :title, content = :content, email = :email, phone_number = :phoneNumber WHERE id = 1", ["title" => $title, "content" => $content, "email" => $email, "phoneNumber" => $phoneNumber])->affectedRows();

        // Always return true if code gets here
        return true;
    }

}