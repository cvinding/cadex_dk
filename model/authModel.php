<?php
namespace MODEL;

class AuthModel extends \MODEL\BASE\Model {

    public function authenticate(string $username, string $password) : bool {
        $authenticateResponse = $this->sendPOST("/auth/authenticate", ["username" => $username, "password" => $password]);
    
        if($this->lastHTTPCode !== 201) {
            return false;
        }

        $authenticateResponse = $authenticateResponse["result"];

        $validateResponse = $this->sendPOST("/auth/validate", ["token" => $authenticateResponse["token"]]);

        if($this->lastHTTPCode !== 200) {
            return false;
        }

        $validateResponse = $validateResponse["result"];

        session_regenerate_id();

        \SESSION\Session::set("LOGIN/STATUS", true);
        \SESSION\Session::set("API/TOKEN", $authenticateResponse["token"]);
        \SESSION\Session::set("USER/ID", $validateResponse["user"]);
        \SESSION\Session::set("USER/SECURITY_GROUPS", $validateResponse["securityGroups"]);

        return true;
    }



}