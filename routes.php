<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

/**
 * Auth endpoint routes
 */

$router->post("/auth/authenticate", "Auth@authenticate");
$router->post("/auth/validate", "Auth@validate");

/**
 * Company endpoint routes
 */


 
/**
 * News endpoint routes
 */

/*$router->get("/news/get", "NewsView@get");
$router->get("/news/get/(\d+)", "NewsView@get");

$router->post("/news/create", "News@create");

$router->put("/news/update/(\d+)", "News@update");

$router->delete("/news/delete/(\d+)", "News@delete");*/

/**
 * Product endpoint routes
 */

$router->get("/product/get", "ProductView@get");
$router->get("/product/get/(\d+)", "ProductView@get");

$router->post("/product/create", "Product@create");
$router->post("/product/uploadImage/(\d+)/(true|false)", "Product@uploadImage");

$router->put("/product/update/(\d+)", "Product@update");

$router->delete("/product/delete/(\d+)", "Product@delete");