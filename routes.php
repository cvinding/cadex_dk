<?php

/**
 *
 * Use this file to define routes
 * Path example: /profile/id/[0-9]+/[a-zA-Z_]+ => /profile/id/0/Christian_Vinding_Rasmussen
 * You can use regex to define parameters in a path
 */

$router->get("/", function() {
    http_response_code(200);
    exit(json_encode(["result" => "The cadex.dk API", "status" => true]));
});

$router->get("/healthz", function() {
    
    $database = new \DATABASE\MYSQLI\Database();

    $result = $database->query("SELECT NOW()")->fetchAssoc();

    if(empty($result)) {

        $httpCode = 400;
        $status = false;

    } else {

        $httpCode = 200;
        $status = true;
    }

    http_response_code($httpCode);

    exit(json_encode(["status" => $status]));
});

/**
 * Auth endpoint routes
 */

$router->post("/auth/authenticate", "Auth@authenticate");
$router->post("/auth/validate", "Auth@validate");

/**
 * Company endpoint routes
 */

$router->get("/company/information", "CompanyView@getAbout");

$router->put("/company/edit", "Company@editAbout", ["IT_SG"]);

$router->get("/company/getLogs", "CompanyView@getLogs", ["IT_SG"]);
$router->get("/company/getLogs/(\d+)", "CompanyView@getLogs", ["IT_SG"]);

$router->get("/company/getLogs/(\d{4})-(\d{2})-(\d{2})", "CompanyView@getLogsByDate", ["IT_SG"]);
$router->get("/company/getLogs/(\d{4})-(\d{2})-(\d{2})/(\d+)", "CompanyView@getLogsByDate", ["IT_SG"]);

$router->get("/company/getLogs/(\d{1,3})-(\d{1,3})-(\d{1,3})-(\d{1,3})", "CompanyView@getLogsByIP", ["IT_SG"]);
$router->get("/company/getLogs/(\d{1,3})-(\d{1,3})-(\d{1,3})-(\d{1,3})/(\d+)", "CompanyView@getLogsByIP", ["IT_SG"]);

$router->get("/company/getLogs/(create|read|update|delete|authenticate)", "CompanyView@getLogsByAction", ["IT_SG"]);
$router->get("/company/getLogs/(create|read|update|delete|authenticate)/(\d+)", "CompanyView@getLogsByAction", ["IT_SG"]);

$router->get("/company/getLogs/(\w+)", "CompanyView@getLogsByUserID", ["IT_SG"]);
$router->get("/company/getLogs/(\w+)/(\d+)", "CompanyView@getLogsByUserID", ["IT_SG"]);
 
/**
 * News endpoint routes
 */

$router->get("/news/getAll", "NewsView@getAll", true);
$router->get("/news/getAll/(\d+)", "NewsView@getAll", true);

$router->get("/news/get/(\d+)", "NewsView@get", true);

$router->post("/news/create", "News@create", ["IT_SG"]);

$router->put("/news/update/(\d+)", "News@update", ["IT_SG"]);

$router->delete("/news/delete/(\d+)", "News@delete", ["IT_SG"]);

/**
 * Product endpoint routes
 */

$router->get("/product/getAll", "ProductView@getAll");
$router->get("/product/getAll/(\d+)", "ProductView@getAll");

$router->get("/product/getAll/img/(\d+)", "ProductView@getAllSetImages");
$router->get("/product/getAll/img/(\d+)/(\d+)", "ProductView@getAllSetImages");

$router->get("/product/get/(\d+)", "ProductView@get");

$router->post("/product/create", "Product@create", ["IT_SG"]);
$router->post("/product/uploadImage/(\d+)/(true|false)", "Product@uploadImage", ["IT_SG"]);

$router->put("/product/update/(\d+)", "Product@update", ["IT_SG"]);

$router->delete("/product/delete/(\d+)", "Product@delete", ["IT_SG"]);