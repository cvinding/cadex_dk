<?php
namespace CONFIG;

/**
 *
 * A config file for database settings
 *
 */

return [

    "db-01" => [

        "DATABASE_DRIVER" => "mysql",

        "HOSTNAME" => "192.168.10.50",

        "USERNAME" => "service.cadex-api",

        "PASSWORD" => "SecureCadex-2960",

        "DATABASE" => "cadex-api",

        "CHARSET" => "utf8mb4"
    ],

    "db-02" => [

        "DATABASE_DRIVER" => "mysql",

        "HOSTNAME" => "192.168.20.50",

        "USERNAME" => "service.cadex-api",

        "PASSWORD" => "SecureCadex-2960",

        "DATABASE" => "cadex-api",

        "CHARSET" => "utf8mb4"
    ]
];