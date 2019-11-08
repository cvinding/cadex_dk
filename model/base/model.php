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

    protected function sendGET(string $endpoint, bool $cache = true) : array {

        if(!$cache) {
            return $this->sendRequest("GET", $endpoint);
        }

        $exists = \HELPER\Cacher::cacheExists($endpoint);

        if($exists) {

            $response = \HELPER\Cacher::getCache($endpoint);

            if(isset($response["expires"]) && $response["expires"] < time()) {

                $response = $this->sendRequest("GET", $endpoint);
                \HELPER\Cacher::cache($endpoint, $response);
            } 

        } else {

            $response = $this->sendRequest("GET", $endpoint);
            \HELPER\Cacher::cache($endpoint, $response);
        }

        return $response;
    }

    protected function sendPOST(string $endpoint, array $data, bool $sendAsJSON = false) {
        return $this->sendRequest("POST", $endpoint, $data, $sendAsJSON);
    } 

    protected function sendPUT(string $endpoint, array $data, bool $sendAsJSON = false) {
        return $this->sendRequest("PUT", $endpoint, $data, $sendAsJSON);
    }

    protected function sendDELETE(string $endpoint) {
        return $this->sendRequest("DELETE", $endpoint);
    }

    protected function sendBPOST(string $endpoint, string $data) {
        return $this->sendBRequest("POST", $endpoint, $data);
    }

    protected function sendBPUT(string $endpoint, string $data) {
        return $this->sendBRequest("PUT", $endpoint, $data);
    }

    private function sendRequest(string $method, string $endpoint, array $data = [], bool $sendAsJSON = false) {

        if(!in_array(strtoupper($method), $this->validRequestMethods)) {
            throw new \Exception("Invalid request method used for API calls.");  
        }

        $endpoint = (substr($endpoint, 0, 1) === "/") ? $endpoint : "/" . $endpoint;

        if($this->cURL === false) {
            $this->cURL = curl_init();
            curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
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
            
            if($sendAsJSON) {

                curl_setopt($this->cURL, CURLOPT_POSTFIELDS, json_encode($data));        

            } else {

                curl_setopt($this->cURL, CURLOPT_POSTFIELDS, http_build_query($data));        
            }
        }

        $rawResponse = curl_exec($this->cURL);

        $this->lastHTTPCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        $response = json_decode($rawResponse, true);

        if(json_last_error() !== 0) {
            throw new \Exception("Malformed JSON response. Unable to construct data response.");  
        }

        return $response;
    }

    private function sendBRequest(string $method, string $endpoint, string $data) {

        if(!in_array(strtoupper($method), $this->writableRequestMethods)) {
            throw new \Exception("Invalid request only use POST & PUT");  
        }

        $endpoint = (substr($endpoint, 0, 1) === "/") ? $endpoint : "/" . $endpoint;

        if($this->cURL === false) {
            $this->cURL = curl_init();
            curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
        }

        curl_setopt($this->cURL, CURLOPT_URL, $this->baseURL . $endpoint);
        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        
        $httpHeaders = [
            "Content-Type: text/plain"
        ];

        $token = \SESSION\Session::get("API/TOKEN");
 
        if($token !== false) {
            $httpHeaders = array_merge(["Authorization: Bearer " . $token], $httpHeaders);
        }
    
        curl_setopt($this->cURL, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data); 

        $rawResponse = curl_exec($this->cURL);

        $this->lastHTTPCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        $response = json_decode($rawResponse, true);

        if(json_last_error() !== 0) {
            throw new \Exception("Malformed JSON response. Unable to construct data response.");  
        }

        return $response;
    }

    private function cacheImages(array $products) {

        $images = [];

        $cacheName = "products.json";

        foreach($products as $index => $product) {

            if(!isset($product["images"]) || empty($product["images"])) {
                continue;
            }

            $images[$product["id"]] = $product["images"]; 
            $products[$index]["images"] = $cacheName;
        }

        \HELPER\ImageCacher::cache($images, $cacheName);

        return $products;
    }

    public function __destruct() {
        ($this->cURL !== false) ? curl_close($this->cURL) : null;
    }

}