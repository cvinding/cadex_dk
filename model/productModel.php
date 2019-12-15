<?php
namespace MODEL;

class ProductModel extends \MODEL\BASE\Model {

    public function getProducts(int $page = 1, bool $cache = true) {
        return $this->sendGET("/product/getAll/" . $page, $cache);
    }

    public function getProductsImg(int $images, int $page = 1, bool $cache = true) {
        return $this->sendGET("/product/getAll/img/" . $images . "/" . $page, $cache);
    }

    public function getProductById($id, $cache = true) {

        $response = $this->sendGET("/product/get/" . $id, $cache);

        if(!$response["status"]) {
            return [];
        }

        return $response;
    }

    public function getAll() {

        $allProducts = [];

        $loop = true;
        $i = 1;
        while($loop) {

            $result = $this->getProductsImg(0, $i, false);

            if($result["status"] === false || $this->lastHTTPCode === 404 || $i === 10) {
                break;
            } 

            //TODO: fix API endpoint

            $allProducts = array_merge($allProducts, $result["result"]["products"]);

            $i++;            
        }

        return $allProducts;
    }

    public function addProduct(string $name, string $description, string $price) {

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

        return true;
    }

    public function editProduct(int $id, string $name, string $description, float $price, array $imagesToDelete = []){
        
        $data = [
            "name" => $name, 
            "description" => $description, 
            "price" => $price
        ];

        if(!empty($imagesToDelete)) {
            $data["imagesToDelete"] = $imagesToDelete;
        }
        
        $response = $this->sendPUT("/product/update/" . $id, $data, true);

        if(!$response["status"]) {
            return false;
        }

        if(isset($_FILES["imageUpload"]) && sizeof($_FILES["imageUpload"]["name"]) > 0) {
            
            $files = $_FILES["imageUpload"];

            for($i = 0; $i < sizeof($files["name"]); $i++) {
                $image = [
                    "name" => $files["name"][$i], 
                    "type" => $files["type"][$i], 
                    "tmp_name" => $files["tmp_name"][$i], 
                    "error" => $files["error"][$i], 
                    "size" => $files["size"][$i]
                ];

                $this->uploadImage($id,$image);
            }
        }
        
        return true;
    }

    private function uploadImage(int $productId, array $image, bool $thumbnail = false) {
        
        $endpoint = "/product/uploadImage/" . $productId . "/";

        $endpoint .= ($thumbnail) ? "true" : "false";

        $response = $this->sendBPOST($endpoint, file_get_contents($image["tmp_name"]));

        return $response["status"];
    }

    public function deleteProduct(int $id) : bool {
        $response = $this->sendDELETE("/product/delete/" . $id);
    
        return $response["status"];
    }

    public function resetPrices() {
        $response = $this->sendPUT("/product/reset", []);

        return $response["status"];
    }
    
}