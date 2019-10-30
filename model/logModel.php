<?php
namespace MODEL;

/**
 * Class LogModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The LogModel handles everything from adding logs to outputting logs
 */
class LogModel extends \MODEL\BASE\Model {
    
     /**
     * __construct() call parent::__construct() for setting the Database instance
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * log() is the method used by \HELPER\Logger::log() to log an user action
     * @param int $userID
     * @param int $ipID
     * @param int $action
     * @param int $message
     * @return bool
     */
    public function log(int $userID, int $ipID, int $action, int $message) : bool {
        return ($this->database->query("INSERT INTO logs (user_id, ip_id, action_id, message_id) VALUES(:uid, :iid, :aid, :mid)", ["uid" => $userID, "iid" => $ipID, "aid" => $action, "mid" => $message])->affectedRows() > 0);
    }

    /**
     * getLogs() is used to return all log entries
     * @param int $page
     * @return array
     */
    public function getLogs(int $page) : array {

        $sql = "SELECT * FROM full_log ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "limit" => $this->getLimit($page), 
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * getLogsByAction() is used to return all logs by which action was done
     * @param string $action
     * @param int $page
     * @return void
     */
    public function getLogsByAction(string $action, int $page) : array {

        $sql = "SELECT * FROM full_log WHERE action = :action ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";
        
        $bindable = [
            "action" => $action,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * getLogsByUserID() is used for returning all logs by which user created the entry
     * @param string $userID
     * @param int $page
     * @return array
     */
    public function getLogsByUserID(string $userID, int $page) : array {

        $sql = "SELECT * FROM full_log WHERE user = :user ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "user" => $userID,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * getLogsByIP() is used for returning all logs by which IP was the remote address
     * @param string $ip
     * @param int $page
     * @return void
     */
    public function getLogsByIP(string $ip, int $page) : array {
        
        // IP comes in like this 127-0-0-1, replace the '-' with '.'
        $realIP = str_replace("-", ".", $ip);

        // Check if IP is valid
        if(filter_var($realIP, FILTER_VALIDATE_IP) === false) {
            return ["__error" => "IP is invalid."];
        }

        $sql = "SELECT * FROM full_log WHERE ip = :ip ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "ip" => $realIP,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * getLogsByDate() is used for returning all logs by what date the log was created on
     * @param string $date
     * @param int $page
     * @return array
     */
    public function getLogsByDate(string $date, int $page) : array {
        
        // Check if date is valid
        $valid = \HELPER\DateHelper::isValidDate($date, "Y-m-d");

        // If date is not valid return error
        if(!$valid) {
            return ["__error" => "Invalid date. Date format expected: 'Y-m-d'"];
        }  

        $sql = "SELECT * FROM full_log WHERE DATE(created_at) = :date ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "date" => $date,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * registerIP() is a method for seleting the ID of an already registered IP, or register an IP and then get the ID
     * @param string $ip
     * @param bool $register
     * @return int
     */
    public function registerIP(string $ip, bool $register) : int {

        $ip = $this->database->query("SELECT id FROM ips WHERE ip = :ip", ["ip" => $ip])->fetchAssoc();

        if(empty($ip) && $register) {

            $id = $this->database->query("INSERT INTO ips (ip) VALUES (:ip)",["ip" => $ip])->getLastAutoID();

        } else if(!empty($ip)) {

            $id = $ip[0]["id"];
        
        } else {

            $id = 0;
        }

        return $id;
    }


    /**
     * registerUser() is a method for seleting the ID of an already registered user, or register an user and then get the ID
     * @param string $username
     * @param bool $register
     * @return int
     */
    public function registerUser(string $username, bool $register) : int {

        $user = $this->database->query("SELECT id FROM users WHERE username = :username", ["username" => $username])->fetchAssoc();

        if(empty($user) && $register) {

            $id = $this->database->query("INSERT INTO users (username) VALUES (:username)",["username" => $username])->getLastAutoID();

        } else if(!empty($user)) {

            $id = $user[0]["id"];
        
        } else {

            $id = 0;
        }

        return $id;
    }

}