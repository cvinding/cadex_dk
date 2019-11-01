<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

$router->get("/", "HomeView@index");

$router->get("/products", "ProductView@index");

$router->get("/news", "NewsView@index", ["LOGIN/STATUS" => true]);
$router->get("/news/p/(\d+)", "NewsView@index", ["LOGIN/STATUS" => true]);

$router->get("/login", "LoginView@index", ["LOGIN/STATUS" => false]);
$router->post("/login/authenticate", "LoginController@authenticate", ["LOGIN/STATUS" => false]);

$router->get("/logout", function(\Request $request) {

    session_destroy();

    header("location: /");

}, ["LOGIN/STATUS" => true]);