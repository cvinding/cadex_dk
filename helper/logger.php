<?php
namespace HELPER;

class Logger {

//SELECT l.id, u.username, i.ip, la.action, lm.message, l.created_at FROM logs l INNER JOIN users u ON l.user_id = u.id INNER JOIN ips i ON l.ip_id = i.id INNER JOIN log_actions la ON l.action_id = la.id INNER JOIN log_messages lm ON l.message_id = lm.id 

    /**
     * log() is a static function which uses the methods in LogModel to create an easy way for all the code to log activity
     * @param string $username
     * @param string $ip
     * @param int $action
     * @param int $message
     * @param bool $register = true
     */
    public static function log(string $username, string $ip, int $action, int $message, bool $register = true) : bool {

        $logModel = new \MODEL\LogModel();

        $ipID = $logModel->registerIP($ip, $register);
        $userID = $logModel->registerUser($username, $register);

        return $logModel->log($userID, $ipID, $action, $message);
    }

}