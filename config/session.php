<?php
namespace CONFIG;

/**
 *
 * A config file for setting default session variables
 *
 */

return [

    "LOGIN/STATUS" => false,
    
    "USER/ID" => false,

    "API/TOKEN" => false,
    
    "CSRF/TOKEN" => bin2hex(random_bytes(32))

];