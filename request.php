<?php
// Set Access-Control-Allow-Methods to POST, GET, PUT, DELETE (CRUD) & OPTIONS
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

/**
 * Class Request
 * @author Christian Vinding Rasmussen
 * Request is a class for defining a client Request
 */
class Request {

    /**
     * An array for settings which request method is able to get an output from getBody()
     * @var array $writeableMethods
     */
    private $writeableMethods = ["POST", "PUT"];

    /**
     * Request constructor.
     * Populate the Request class' variables
     */
    public function __construct() {

        //Create and set $_SERVER in Request class 
        $this->_SERVER();

        // Explode $fullPath into array
        $uri = explode("/", $this->requestUri);

        // Shift first entry, as it will always be nothing
        array_shift($uri);

        // Shift next entry and set it as $class
        $this->class = (($class = array_shift($uri)) !== "") ? $class : "index";

        // If there is more entries set; shift next entry else set action false
        $this->action = (isset($uri[0]) && !empty($uri[0])) ? array_shift($uri) : false;

        // Set URI arguements
        $this->uriArgs = $uri;
    }

    /**
     * _SERVER() is used to set all $_SERVER variables as properties in this class
     * @return void
     */
    private function _SERVER() : void {
        // Filter through each $_SERVER entry and set each entry as a property in the class
        foreach($_SERVER as $header => $value) {

            // Convert from snake_case to camelCase
            $header = $this->snakeCaseToCamelCase($header);

            // Create property and set the $value
            $this->$header = $value;
        }
    }

    /**
     * getBody() is used to return the request body
     * @return array
     */
    public function getBody() : array {
        
        // If request method is not writeable then return empty array
        if(!in_array($this->requestMethod, $this->writeableMethods)) {
            return [];
        }

        $input = file_get_contents("php://input");

        // Get $json from $input
        $json = json_decode($input, true);
        $isValidJSON = (json_last_error() === 0);

        // If content type is 'application/json' then get the php://input JSON input
        if($this->contentType === "application/json" && $isValidJSON) {

            $body = $json;            

        // If content type is not 'application/json' and not nothing, then set $body to $_POST
        } else if(($this->contentType !== "" && $this->contentType !== "text/plain") && $this->requestMethod === "POST") {

            $body = $_POST;

        // If content type is 'application/x-www-form-urlencoded' and request method is PUT then check if input is JSON 
        } else if($this->contentType === "application/x-www-form-urlencoded" && $this->requestMethod === "PUT") {

            // if valid JSON then set $body to empty array
            if($isValidJSON) {
                
                $body = [];   
            
            // Else set $body to array from form urlendoded data
            } else {

                $body = $this->formUrlencodedToArray($input);
            }

        // Else set an empty $body
        } else {

            if(!is_array($input)) {
                $input = [$input];
            }

            $body = $input;
        }

        // Return the request body
        return $body;
    }

    /**
     * snakeCaseToCamelCase() is used for converting snake_case to camelCase
     * @param string $string
     * @return string
     */
    private function snakeCaseToCamelCase(string $snakeCase) : string {

        // Create $temporary and set $string to lowercase
        $temporary = strtolower($snakeCase);

        // If there are any underscores in $temporary, then convert the string
        if(strpos($temporary, "_") !== false) {
            // Explode on underscore
            $explodedTemporary = explode("_",$temporary);

            $temporary = "";
            // Loop through each $explodedTemporary entry
            for($i = 0; $i < sizeof($explodedTemporary); $i++) {

                // Don't set first entry to uppercase on first letter
                if($i === 0) {
                    $temporary .= $explodedTemporary[$i];
                    continue;
                }
                
                // Set uppercase on first letter
                $temporary .= ucfirst($explodedTemporary[$i]);
            }
        }
        
        // Return the formatted string
        return $temporary;
    }

    /**
     * formUrlencodedToArray() used to format a form urlencoded string to an array
     * @param string $formUrlencoded
     * @return array
     */
    private function formUrlencodedToArray(string $formUrlencoded) : array {

        $temporary = [];

        // Explode string on '&' as it is the delimeter for arguements in a form urlencoded string
        $exploded = explode("&", $formUrlencoded);

        // Loop through and seperate $key => $value, and insert them into the $temporary array
        foreach($exploded as $entry) {
            $explodedEntry = explode("=", $entry);

            $temporary[$explodedEntry[0]] = urldecode($explodedEntry[1]);
        }

        // Return $temporary
        return $temporary;
    }

}