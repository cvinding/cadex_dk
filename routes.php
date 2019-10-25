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

$router->get("/news/get", "NewsView@get", true);
$router->get("/news/get/(\d+)", "NewsView@get", true);

$router->post("/news/create", "News@create", ["IT_SG"]);

$router->put("/news/update/(\d+)", "News@update", ["IT_SG"]);

$router->delete("/news/delete/(\d+)", "News@delete", ["IT_SG"]);

/**
 * Product endpoint routes
 */

$router->get("/product/getAll", "ProductView@getAll");
$router->get("/product/getAll/img/(\d+)", "ProductView@getAll");
$router->get("/product/get/(\d+)", "ProductView@get");

$router->post("/product/create", "Product@create", ["IT_SG"]);
$router->post("/product/uploadImage/(\d+)/(true|false)", "Product@uploadImage", ["IT_SG"]);

$router->put("/product/update/(\d+)", "Product@update", ["IT_SG"]);

$router->delete("/product/delete/(\d+)", "Product@delete", ["IT_SG"]);