<?php
namespace HELPER;

class Logger {

    //SELECT l.id, u.username, la.action, lm.message, l.created_at FROM logs l INNER JOIN users u ON l.user_id = u.id INNER JOIN log_actions la ON l.action_id = la.id INNER JOIN log_messages lm ON l.message_id = lm.id 

    public static function log(string $username, string $ip, int $action, int $message, bool $register = true) : bool {

        $authModel = new \MODEL\AuthModel();

        $id = $authModel->registerUser($username, $register);

        $database = new \DATABASE\MYSQLI\Database();

        return ($database->query("INSERT INTO logs (user_id, action_id, message_id) VALUES(:uid, :aid, :mid)", ["uid" => $id, "aid" => $action, "mid" => $message])->affectedRows() > 0);
    }

}