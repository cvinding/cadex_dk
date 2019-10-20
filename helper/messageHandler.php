<?php
namespace HELPER;

/**
 * Class MessageHandler
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * A simple message handler class for outputting a respone in the view
 */
class MessageHandler {

    private static $messages = [];

    /**
     * attachMessage() is used for attaching response message
     * @param string $message
     * @param int $httpCode
     * @return void
     */
    public static function attachMessage(string $message, int $httpCode = 200) : void {
        self::$messages[] = ["message" => $message, "httpCode" => $httpCode];
    }

    /**
     * getMessages() returns all messages
     * @return array
     */
    public static function getMessages() : array {
        return self::$messages;
    }

}