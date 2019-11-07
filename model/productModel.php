<?php
namespace MODEL;

class ProductModel extends \MODEL\BASE\Model {

    public function getProducts(int $page = 1, bool $cache = true) {
        return $this->sendGET("/product/getAll/" . $page, $cache);
    }

    public function getProductById($id) {

        $response = $this->sendGET("/product/get/" . $id);

        if(!$response["status"]) {
            return [];
        }

        return $response;
    }

    public function addProduct(string $name, string $description, string $price) {
        //var_dump($_FILES);

        // Create product
        $result = $this->sendPOST("/product/create", [
            "name" => $name,
            "description" => $description,
            "price" => $price
        ]);

        if(!$result["status"]) {
            return false;
        }

        $productId = $result["result"]["id"];

      
        //Upload thumbnail
        if(!isset($_FILES["thumbnail"]) || $_FILES["thumbnail"]["error"] === 4) {
            return false;
        }

        $this->uploadImage($productId, $_FILES["thumbnail"], true);
        //Upload andre billeder

        if(isset($_FILES["images"]) && $_FILES["images"]["error"][0] !== 4) {

            $file = $_FILES["images"];

            for($i = 0; $i < sizeof($file["name"]); $i++) {
                $image = [
                    "name" => $file["name"][$i], 
                    "type" => $file["type"][$i], 
                    "tmp_name" => $file["tmp_name"][$i], 
                    "error" => $file["error"][$i], 
                    "size" => $file["size"][$i]
                ];

                $this->uploadImage($productId,$image);
            }
        }

    }

    private function uploadImage(int $productId, array $image, bool $thumbnail = false) {
        
        $endpoint = "/product/uploadImage/" . $productId . "/";

        $endpoint .= ($thumbnail) ? "true" : "false";

//var_dump($endpoint);

        $response = $this->sendBPOST($endpoint, file_get_contents($image["tmp_name"]));

        var_dump($response);
    }

}