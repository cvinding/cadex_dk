<?php
namespace VIEW;

/**
 * Class ProductView
 * @package VIEW
 * @author Christian Vinding Rasmussen
 * ProductView is the endpoint for Creating, Reading, Updating and Deleting products
 */
class ProductView extends \VIEW\BASE\View {

    /**
     * An instance of \MODEL\ProductModel
     * @var \MODEL\ProductModel $productModel
     */
    private $productModel;

    /**
     * __construct() is used for setting the $request class variable and creating a new instance of the \MODEL\ProductModel
     * @var \Request $request
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->productModel = new \MODEL\ProductModel();
    }

    /**
     * get() is used for outputting a list of products this endpoint has to offer
     * @param int $id
     * @return void
     */
    public function get(int $id) : void {

        $data = $this->productModel->getProduct($id);

        $httpCode = 200;
        $status = true;

        if(empty($data)) {
            $httpCode = 404;
            $status = false;
        } 

        http_response_code($httpCode);

        // Output the JSON data
        exit(json_encode(["result" => $data, "status" => $status]));
    }

    /**
     * getAll() is used to return all products + specify the amount of images the API should output
     * @param string $img = ""
     * @param int $imgAmount = 1
     * @return void 
     */
    public function getAll(string $img = "", int $imgAmount = 1) : void {

        $data = $this->productModel->getProducts($imgAmount);

        $httpCode = 200;
        $status = true;

        if(empty($data)) {
            $httpCode = 404;
            $status = false;
        } 

        http_response_code($httpCode);

        // Output the JSON data
        exit(json_encode(["result" => $data, "status" => $status]));
    }


}