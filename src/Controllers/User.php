<?php
namespace App\Controllers;

use App\Models\Session;
use System\Controller;
use System\Http\RedirectResponse;
use System\Http\Request;

// THE SHIT BEGINS...
class User extends Controller {
    public function login(Request $request, array $args=[]) {
        if(!$request->data["session"]) return $this->renderTemplate("user.login");
        else return RedirectResponse::toRoute("home");
    }
    public function handle_login(Request $request, array $args=[]) {
        if($request->data["session"]) return RedirectResponse::toRoute("home");
        else {
            $username = strtolower($request->postParams["username"] ?? "");
            $password = $request->postParams["password"] ?? "";
            $user = \App\Models\User::getByUsername($username);
            if($user) {
                if(password_verify($password, $user->password)) {
                    $session = new Session(null,$user);
                    $session->save();
                    $resp = RedirectResponse::toRoute("home");
                    $resp->setCookie("session_token",$session->token, $session->expires);
                    return $resp;
                } else $error = true;
            } else $error = true;
            if($error) {
                return $this->renderTemplate("user.login",["error"=>true,"previousLogin"=>$username]);
            }
        }
    }
}