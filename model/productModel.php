<?php
namespace MODEL;

class ProductModel extends \MODEL\BASE\Model {

    public function getAll(int $page = 1) {
        return $this->sendGET("/product/getAll/" . $page)["result"];
    }

    public function getProductById($id) {

        $response = $this->sendGET("/product/get/" . $id);

        if(!$response["status"]) {
            return [];
        }

        return $response;
    }

}