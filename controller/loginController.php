<?php
namespace CONTROLLER;

class LoginController extends \CONTROLLER\BASE\Controller {

    public function authenticate() {

        $authModel = new \MODEL\AuthModel();

        $result = $authModel->authenticate($_POST["username"], $_POST["password"]);

        if($result) {
            header("location: /");
        } else {
            header("location: /login");
        }
    }


}