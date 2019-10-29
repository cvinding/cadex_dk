<?php
namespace MODEL;

// Require the JWT library
require APP_ROOT . '/vendor/autoload.php';

/**
 * Class AuthModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The model for granting access for users using the API
 */
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

    /**
     * __construct() is used for loading the JWT secret and initializing the Database class
     */
    public function __construct() {
        parent::__construct();
        try {

            $this->secret = \HELPER\ConfigLoader::load("config/secret.php", ["SECRET"])["SECRET"];

        } catch(\Exception $exception) {
            exit($exception);
        }

        $this->database = new \DATABASE\MYSQLI\Database();
    }

    /**
     * authenticateUser() is used to authenticate an user in our AD LDAP 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticateUser(string $username, string $password) : bool {

        $this->username = $username;
        $this->securityGroups[] = "IT_SG";

        //TODO L D A P

        return in_array($username, ["chvr","kemv","tola"]);
    }

    /**
     * createToken() is used to create JSON Web Tokens
     * @param array $claims = []
     * @return string
     */
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

    /**
     * getTokenClaim() is used to return requested claim from a JSON Web Token
     * @param string $token
     * @param string $claim
     * @return mixed
     */
    public function getTokenClaim(string $token, string $claim) {

        try {
            
            // Get the $payload from the $token
            $payload = \ReallySimpleJWT\Token::getPayload($token, $this->secret);

        } catch (\Exception $exception) {
            return false;
        }

        // Return the specified claim
        return $payload[$claim];
    }

    /**
     * validateToken() is used to validate a JSON Web Token
     * @param string $token
     */
    public function validateToken(string $token) : bool {

        try {

            // Validate the token
            $isValid = \ReallySimpleJWT\Token::validate($token, $this->secret);

        } catch (\Exception $exception) {
            return false;
        }

        // Return result of validation
        return $isValid;
    }
    
    /**
     * generation of secrets, really need to be finished but its ok no one needs to generate new secrets anyway :I
     */
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