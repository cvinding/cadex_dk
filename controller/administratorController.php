<?php
namespace CONTROLLER;

class AdministratorController extends \CONTROLLER\BASE\Controller {

    public function submit(string $action) {
       
        $removeKeys = ["add", "delete", "edit", "confirm", "CSRF-TOKEN"];

        foreach($removeKeys as $key) {
            if(isset($_POST[$key])) {
                unset($_POST[$key]);
            }
        }

        $instance = \HELPER\Dynamic::instance($this->request->action, "Model");
        $result = \HELPER\Dynamic::call($instance, $action . ucfirst($this->request->action), $_POST);

        if($result) {
            
            $types = ["news" => "news post", "product" => "product"];
            $actions = ["add" => "added", "edit" => "edited", "delete" => "deleted"];

            \HELPER\MessageHandler::attachMessage("You have successfully " . $actions[$action] . " a " . $types[$this->request->action]);

        } else {

            $types = ["news" => "news post", "product" => "product"];
            $actions = ["add" => "added", "edit" => "edited", "delete" => "deleted"];
            
            \HELPER\MessageHandler::attachMessage("Unable to " . $action . " a " . $types[$this->request->action], "danger");
        }

    }

    public function reset() {
        $model = new \MODEL\ProductModel();

        $model->resetPrices();

        header("location: /administrate/product");
    }

}