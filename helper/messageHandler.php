<?php
namespace HELPER;

/**
 * Class MessageHandler
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * A simple message handler class for outputting a response in the view
 */
class MessageHandler {

    /**
     * The array for storing all system messages
     * @var array $messages
     */
    private static $messages = [];

    /**
     * attachMessage() is used for attaching response message
     * @param string $message
     * @param int $httpCode
     * @return void
     */
    public static function attachMessage(string $message, string $type = "success") : void {
        self::$messages[] = ["message" => $message, "type" => $type];
    }

    /**
     * getMessages() returns all messages
     * @return array
     */
    public static function getMessages() : array {
        return self::$messages;
    }

}