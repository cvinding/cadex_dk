<?php
namespace CONTROLLER;

/**
 * Class ProductController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * ProductController is used giving the Product endpoint user actions in form of POST,PUT,DELETE requests
 */
class ProductController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of this controller's model
     * @var \MODEL\ProductModel $productModel
     */
    private $productModel;

    /**
     * __construct() is used for setting the $model this controller will be using
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->productModel = new \MODEL\ProductModel();
    }

    /**
     * create() is used to create a product and then send a message to the view
     * @param string $name
     * @param string $description
     * @param float $price
     * @return void
     */
    public function create(string $name, string $description, float $price) : void {
    
        // Try and create a new product
        $result = $this->productModel->createProduct($name, $description, $price);

        // Attach message based on outcome
        if($result) {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 5);

            \HELPER\MessageHandler::attachMessage("You have successfully created a new product.", 201);
        } else {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 6);

            \HELPER\MessageHandler::attachMessage("An error occurred while trying to create a new product.", 500);
        }
    }

    /**
     * uploadImage() is used to upload an image and return a message for the view to render
     * @param int $id
     * @param string $thumbnail
     * @param string $image = ""
     * @return void
     */
    public function uploadImage(int $id, string $thumbnail, string $image = "") {

        // Upload image
        $status = $this->productModel->uploadImage($id, $thumbnail, $image);

        // Send the appropriate message
        switch ($status) {
            case $status === 0:

                \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 9);

                \HELPER\MessageHandler::attachMessage("Missing image upload.", 400);
                break;
            case $status === 1:

                \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 10);

                \HELPER\MessageHandler::attachMessage("Image size is too large. Image size cannot exceed 20mib.", 400);
                break;
            case $status === 2:

                \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 11);

                \HELPER\MessageHandler::attachMessage("Invalid image type. Endpoint only supports JPEG, PNG and GIF uploads.", 400);
                break;
            case $status === true:

                \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 7);

                \HELPER\MessageHandler::attachMessage("You have successfully uploaded a new image.", 201);
                break;
            default:
                
                \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 8);

                \HELPER\MessageHandler::attachMessage("An error occurred while trying to upload a new image.", 500);
                break;
        }
    }

    /**
     * update() is used to call the $productModel for updating products
     * @param int $id
     * @param string $name
     * @param string $description
     * @param float $price
     * @param array $imagesToDelete = []
     * @return void
     */
    public function update(int $id, string $name, string $description, float $price, array $imagesToDelete = []) : void {

        // Try and update the product
        $result = $this->productModel->updateProduct($id, $name, $description, $price);

        // Delete images if there are any
        if(!empty($imagesToDelete)) {
            $this->productModel->deleteImages($imagesToDelete);
        }

        // Attach message based on outcome
        if($result) {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 12);

            \HELPER\MessageHandler::attachMessage("You have successfully updated a product.", 200);
        } else {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 13);

            \HELPER\MessageHandler::attachMessage("Unable to find product, product could not be updated.", 404);
        }
    }

    /**
     * delete() is used to call the $model for deleting products
     */
    public function delete(int $id) {

        // Try and delete the product
        $result = $this->productModel->deleteProduct($id);

        // Attach message based on outcome
        if($result) {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 4, 14);

            \HELPER\MessageHandler::attachMessage("You have successfully deleted a product.", 200);
        } else {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 4, 15);

            \HELPER\MessageHandler::attachMessage("Unable to find product, product could not be deleted.", 404);
        }

    }
}