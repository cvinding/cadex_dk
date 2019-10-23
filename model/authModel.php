<?php
namespace MODEL;

require APP_ROOT . '/vendor/autoload.php';

class AuthModel extends \MODEL\BASE\Model {

    /**
     * The JWT's secret
     * @var string $secret
     */
    private $secret;

    /**
     * The seconds before the token is usable
     * @var int $notBefore
     */
    private $notBefore = 1;

    /**
     * The seconds before the token expires
     * @var int $expiration
     */
    private $expiration = 3600*6;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var array $securityGroups
     */
    private $securityGroups = [];

    public function __construct() {
        try {

            $this->secret = \HELPER\ConfigLoader::load("config/secret.php", ["SECRET"])["SECRET"];

        } catch(\Exception $exception) {
            exit($exception);
        }
    }

    public function authenticateUser(string $username, string $password) : bool {

        $this->username = $username;
        $this->securityGroups[] = "Web-SG";

        //TODO L D A P

        return true;
    }

    public function createToken(array $claims = []) : string {

        $config = [
            "iss" => "api.cadex.dk",                // Issuer
            "sub" => "User Authorization Token",    // Subject
            "aud" => "api.cadex.dk",                // Audience
            "exp" => time() + $this->expiration,    // Expires
            "nbf" => time() + $this->notBefore,     // Not usable before
            "iat" => time(),                        // Issued at
            "uid" => $this->username,               // Userid
            "sgr" => $this->securityGroups,         // Security groups the user/process is a part of
            //"jti" => "TOKEN ID"
        ];

        // Merge the config with other claims if there are any in $claims
        if(!empty($claims)) {
            $config = array_merge($config, $claims);
        }

        try {
            
            // Use the ReallySimpleJWT library to create a token
            $token = \ReallySimpleJWT\Token::customPayload($config, $this->secret);
            
        } catch (\Exception $exception) {
            exit($exception);
        }

        return $token;
    }

    public function getTokenClaim(string $token, string $claim) {

    }

    public function validateToken(string $token) : bool {
        return true;
    }
    


    private function generateSecret(int $length = 12) : string {
        
        $availableCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789*&!@%^#$";
        
        $secret = "";

        while(true) {

            for($i = 0; $i < $length; $i++) {
                
                $secret .= $availableCharacters[rand(0, strlen($availableCharacters) - 1)];    
            }
    
            

            break;
        }

        var_dump($secret);die;

        return $secret;
    }
}