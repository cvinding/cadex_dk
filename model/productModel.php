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

}