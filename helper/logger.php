<?php
namespace HELPER;

/**
 * Class Logger
 * @package HELPER
 * @package Christian Vinding Rasmussen
 * The Logger class is used for logging API activity and is used in most, if not all, Controller classes
 */
class Logger {

    /**
     * log() is a static function which uses the methods in LogModel to create an easy way for all the code to log activity
     * @param string $username
     * @param string $ip
     * @param int $action
     * @param int $message
     * @param bool $register = true
     */
    public static function log(string $username, string $ip, int $action, int $message, bool $register = true) : bool {

        // New instance of \MODEL\LogModel
        $logModel = new \MODEL\LogModel();

        // Get IPID/Register IP and then get IPID
        $ipID = $logModel->registerIP($ip, $register);

        // Get userID/Register user and then get userID
        $userID = $logModel->registerUser($username, $register);

        // Log this action
        return $logModel->log($userID, $ipID, $action, $message);
    }

}