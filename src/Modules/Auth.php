<?php
namespace App\Modules;

use System\Http\Response;
use System\Module;
use System\Http\Request;
use App\Models\Session;

class Auth extends Module {
    public function run(Request $request, Response $response) {
        $request->data["session"]=false;
        $token = $request->cookies["session_token"] ?? false;
        $clientWrongToken = false;
        if($token) {
            $session = Session::getByToken($token);
            if($session) {
                $session->expires = strtotime("+1 week");
                $session->save();
                $request->data["session"] = $session;
                $response->setCookie("session_token", $session->token, $session->expires);
            } else $clientWrongToken = true;
        } else $clientWrongToken = true;
        if($clientWrongToken) $response->setCookie("session_token", "", time()-10);
    }
}