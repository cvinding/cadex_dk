<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

$router->get("/", "HomeView@index");

$router->get("/products", "ProductView@index");

$router->get("/news", function(\Request $request) {

    $html = "<h3>Here is some secret news</h3>";

    $model = new \MODEL\BASE\Model();

    var_dump($model->sendGET("/news/getAll"));

    exit($html);

}, ["LOGIN/STATUS" => true]);

$router->get("/login", function(\Request $request) {

    \SESSION\Session::set("LOGIN/STATUS", true);

    header("location: /");
    
}, ["LOGIN/STATUS" => false]);

$router->get("/logout", function(\Request $request) {

    session_destroy();

    header("location: /");

}, ["LOGIN/STATUS" => true]);