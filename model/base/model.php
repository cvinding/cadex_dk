<?php
namespace MODEL\BASE;

class Model {

    protected $lastHTTPCode = 0;

    private $baseURL = null;

    private $cURL = false;

    private $validRequestMethods = ["POST", "GET", "PUT", "DELETE"];
    private $writableRequestMethods = ["POST", "PUT"];

    public function __construct() {
        
        try {

            $config = \HELPER\ConfigLoader::load("config/api.php",["PROTOCOL", "BASE_URL"]);

            $this->baseURL = $config["PROTOCOL"] . $config["BASE_URL"];

        } catch (\Exception $exception) {
            exit($exception);
        }

    }

    protected function sendGET(string $endpoint) : array {
   
        $savedResponses = \SESSION\Session::get("API/RESPONSES");
    
        if(!isset($savedResponses[$endpoint]) || (isset($savedResponses[$endpoint]["expires"]) && $savedResponses[$endpoint]["expires"] < time())) {

            $response = $this->sendRequest("GET", $endpoint);

            $savedResponses[$endpoint] = $response;
            $savedResponses[$endpoint]["expires"] = time() + 600;

            \SESSION\Session::set("API/RESPONSES", $savedResponses);

        } else {

            $response = $savedResponses[$endpoint];
        }

        return $response;
    }

    protected function sendPOST(string $endpoint, array $data) {
        return $this->sendRequest("POST", $endpoint, $data);
    } 

    private function sendRequest(string $method, string $endpoint, array $data = []) {

        if(!in_array(strtoupper($method), $this->validRequestMethods)) {
            throw new \Exception("Invalid request method used for API calls.");  
        }

        $endpoint = (substr($endpoint, 0, 1) === "/") ? $endpoint : "/" . $endpoint;

        if($this->cURL === false) {
            $this->cURL = curl_init();
            curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
        }

        curl_setopt($this->cURL, CURLOPT_URL, $this->baseURL . $endpoint);
        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        
        $token = \SESSION\Session::get("API/TOKEN");

        if($token !== false) {
            curl_setopt($this->cURL, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $token
            ]);    
        }
    
        if(in_array(strtoupper($method), $this->writableRequestMethods) && !empty($data)) {
            curl_setopt($this->cURL, CURLOPT_POSTFIELDS, http_build_query($data));    
        }

        $rawResponse = curl_exec($this->cURL);

        $this->lastHTTPCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        $response = json_decode($rawResponse, true);

        if(json_last_error() !== 0) {
            throw new \Exception("Malformed JSON response. Unable to construct data response.");  
        }

        return $response;
    }

    public function __destruct() {
        ($this->cURL !== false) ? curl_close($this->cURL) : null;
    }

}