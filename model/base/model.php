<?php
namespace MODEL\BASE;

class Model {

    private $baseURL = null;

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

    public function sendGET(string $endpoint) : array {
        return $this->sendRequest("GET", $endpoint);
    }

    public function sendPOST(string $endpoint, array $data) {

    } 


    private function sendRequest(string $method, string $endpoint, array $data = []) {

        if(!in_array(strtoupper($method), $this->validRequestMethods)) {
            throw new \Exception("Invalid request method used for API calls.");  
        }

        $endpoint = (substr($endpoint, 0, 1) === "/") ? $endpoint : "/" . $endpoint;

        $cURL = curl_init($this->baseURL . $endpoint);

        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if(in_array(strtoupper($method), $this->writableRequestMethods) && !empty($data)) {
            curl_setopt($cURL, CURLOPT_POSTFIELDS, http_build_query($data));    
        }

        $rawResponse = curl_exec($cURL);

        $response = json_decode($rawResponse, true);

        curl_close($cURL);

        if(json_last_error() !== 0) {
            throw new \Exception("Malformed JSON response. Unable to construct data response.");  
        }

        return $response;
    }

}