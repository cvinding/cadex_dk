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
     * @var \MODEL\ProductModel $model
     */
    private $model;

    /**
     * __construct() is used for setting the $model this controller will be using
     */
    public function __construct() {
        $this->model = new \MODEL\ProductModel();
    }

    /**
     * create() is used to create a product and then send a message to the view
     * @param string $name
     * @param int $price
     * @return void
     */
    public function create(string $name, int $price) : void {
        
        // Try and create a new product
        $result = $this->model->createProduct($name, $price);

        // Attach message based on outcome
        if($result) {
            \HELPER\MessageHandler::attachMessage("You have successfully created a new product.", 201);
        } else {
            \HELPER\MessageHandler::attachMessage("An error occurred while trying to create a new product.", 500);
        }
    }

    /**
     * update() is used to call the $model for updating products
     * @param int $id
     * @param string $name
     * @param int $price
     * @return void
     */
    public function update(int $id, string $name, int $price) : void {

        // Try and update the product
        $result = $this->model->updateProduct($id, $name, $price);

        // Attach message based on outcome
        if($result) {
            \HELPER\MessageHandler::attachMessage("You have successfully updated a product.", 200);
        } else {
            \HELPER\MessageHandler::attachMessage("Unable to find product, product could not be updated.", 404);
        }
    }

    /**
     * delete() is used to call the $model for deleting products
     */
    public function delete(int $id) {

        // Try and delete the product
        $result = $this->model->deleteProduct($id);

        // Attach message based on outcome
        if($result) {
            \HELPER\MessageHandler::attachMessage("You have successfully deleted a product.", 200);
        } else {
            \HELPER\MessageHandler::attachMessage("Unable to find product, product could not be deleted.", 404);
        }

    }
}