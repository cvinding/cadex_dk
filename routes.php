<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

$router->get("/", "HomeView@index");

$router->get("/update", function($request) {

    \SESSION\Session::set("API/RESPONSES", []);

    header("location: /");

});

$router->get("/products", "ProductView@index");
$router->get("/products/p/(\d+)", "ProductView@index");
$router->get("/products/product/(\d+)", "ProductView@getProduct");

$router->get("/news", "NewsView@index", ["LOGIN/STATUS" => true]);
$router->get("/news/p/(\d+)", "NewsView@index", ["LOGIN/STATUS" => true]);

$router->get("/administrate", "AdministratorView@index", ["USER/SECURITY_GROUPS" => "IT_SG"]);

$router->get("/administrate/news", "AdministratorView@list", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->get("/administrate/news/(add|edit|delete)", "AdministratorView@getForm", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/news/(add|edit|delete)/submit", "Administrator@submit", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/news/(edit|delete)/confirm", "AdministratorView@confirm", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/news/edit/form", "AdministratorView@getForm", ["USER/SECURITY_GROUPS" => "IT_SG"]);

$router->get("/administrate/product", "AdministratorView@list", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->get("/administrate/product/(add|edit|delete)", "AdministratorView@getForm", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/product/(add|edit|delete)/submit", "Administrator@submit", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/product/(edit|delete)/confirm", "AdministratorView@confirm", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/product/edit/form", "AdministratorView@getForm", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->post("/administrate/product/reset", "AdministratorController@reset", ["USER/SECURITY_GROUPS" => "IT_SG"]);

$router->get("/administrate/logs", "AdministratorView@logs", ["USER/SECURITY_GROUPS" => "IT_SG"]);
$router->get("/administrate/logs/(\d+)", "AdministratorView@logs", ["USER/SECURITY_GROUPS" => "IT_SG"]);

$router->get("/login", "LoginView@index", ["LOGIN/STATUS" => false]);
$router->post("/login/authenticate", "LoginController@authenticate", ["LOGIN/STATUS" => false]);

$router->get("/logout", function(\Request $request) {

    \SESSION\Session::set("LOGIN/STATUS", false);
    \SESSION\Session::set("USER",[]);
    \SESSION\Session::set("USER/ID", false);
    \SESSION\Session::set("USER/SECURITY_GROUPS",[]);

    header("location: /");

}, ["LOGIN/STATUS" => true]);