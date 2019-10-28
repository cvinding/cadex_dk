<?php
namespace MODEL;

class LogModel extends \MODEL\BASE\Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function log(int $userID, int $ipID, int $action, int $message) : bool {
        return ($this->database->query("INSERT INTO logs (user_id, ip_id, action_id, message_id) VALUES(:uid, :iid, :aid, :mid)", ["uid" => $userID, "iid" => $ipID, "aid" => $action, "mid" => $message])->affectedRows() > 0);
    }

    public function getLogs(int $page) : array {

        $sql = "SELECT * FROM full_log ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "limit" => $this->getLimit($page), 
            "offset" => $this->getOffset($page)
        ];

        $data = $this->database->query($sql, $bindable)->fetchAssoc();

        return $data;
    }

    public function getLogsByAction(string $action, int $page) : array {

        $sql = "SELECT * FROM full_log WHERE action = :action ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";
        
        $bindable = [
            "action" => $action,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    public function getLogsByUserID(string $userID, int $page) : array {

        $sql = "SELECT * FROM full_log WHERE user = :user ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "user" => $userID,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    public function getLogsByIP(string $ip, int $page) : array {
        $realIP = str_replace("-", ".", $ip);

        if(filter_var($realIP, FILTER_VALIDATE_IP) === false) {
            return ["error" => "IP is invalid."];
        }

        $sql = "SELECT * FROM full_log WHERE ip = :ip ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "ip" => $realIP,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    public function getLogsByDate(string $date, int $page) : array {
        
        $valid = \HELPER\DateHelper::isValidDate($date, "Y-m-d");

        if(!$valid) {
            return ["error" => "Invalid date. Date format excepted: 'Y-m-d'"];
        }  

        $sql = "SELECT * FROM full_log WHERE DATE(created_at) = :date ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "date" => $date,
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

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