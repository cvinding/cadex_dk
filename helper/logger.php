<?php
namespace HELPER;

class Logger {

//SELECT l.id, u.username, i.ip, la.action, lm.message, l.created_at FROM logs l INNER JOIN users u ON l.user_id = u.id INNER JOIN ips i ON l.ip_id = i.id INNER JOIN log_actions la ON l.action_id = la.id INNER JOIN log_messages lm ON l.message_id = lm.id 

    public static function log(string $username, string $ip, int $action, int $message, bool $register = true) : bool {

        $user_id = self::registerUser($username, $register);
        $ip_id = self::registerIP($ip, $register);

        $database = new \DATABASE\MYSQLI\Database();

        return ($database->query("INSERT INTO logs (user_id, ip_id, action_id, message_id) VALUES(:uid, :iid, :aid, :mid)", ["uid" => $user_id, "iid" => $ip_id, "aid" => $action, "mid" => $message])->affectedRows() > 0);
    }

    private static function registerUser(string $username, bool $register){

        $database = new \DATABASE\MYSQLI\Database();

        $user = $database->query("SELECT id FROM users WHERE username = :username", ["username" => $username])->fetchAssoc();

        if(empty($user) && $register) {

            $id = $database->query("INSERT INTO users (username) VALUES (:username)",["username" => $username])->getLastAutoID();

        } else if(!empty($user)) {

            $id = $user[0]["id"];
        
        } else {

            $id = 0;
        }

        return $id;
    }

    private static function registerIP(string $ip, bool $register) {

        $database = new \DATABASE\MYSQLI\Database();

        $user = $database->query("SELECT id FROM ips WHERE ip = :ip", ["ip" => $ip])->fetchAssoc();

        if(empty($user) && $register) {

            $id = $database->query("INSERT INTO ips (ip) VALUES (:ip)",["ip" => $ip])->getLastAutoID();

        } else if(!empty($user)) {

            $id = $user[0]["id"];
        
        } else {

            $id = 0;
        }

        return $id;
    }

}