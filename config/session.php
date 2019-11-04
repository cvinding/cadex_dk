<?php
namespace CONFIG;

/**
 *
 * A config file for setting default session variables
 *
 */

return [

    "LOGIN/STATUS" => false,
    
    "USER" => [],
    "USER/ID" => false,
    "USER/SECURITY_GROUPS" => [],

    "API/TOKEN" => false,
    
    "CSRF/TOKEN" => bin2hex(random_bytes(32))

];