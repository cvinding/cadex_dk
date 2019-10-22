<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

$router->get("/product", "ProductView@index");

$router->get("/product/get", "ProductView@get");

$router->get("/product/get/(\d+)", "ProductView@get");

$router->post("/product/create", "Product@create");

$router->put("/product/update/(\d+)", "Product@update");

$router->delete("/product/delete/(\d+)", "Product@delete");

$router->post("/product/uploadImage/(\d+)", "Product@uploadImage");

//$router->put("/product/uploadImages/(\d+)", "Product@uploadImages");


/*$router->get("/product/get", function(Request $request) {
    return (new \VIEW\ProductView($request))->get();
});*/

/*$router->get("/product/get/(\d+)", function(Request $request) {
    return (new \VIEW\ProductView($request))->get($request->getURIArgs()[0]);
});*/

/*$router->post("/product/create", function(Request $request) {

    $action = $request->getAction();
    
    call_user_func_array([new \CONTROLLER\ProductController(), $action], $request->getBody());

    call_user_func_array([new \VIEW\ProductView($request), $action],[]);
   
});*/
