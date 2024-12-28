<?php
namespace App\Controllers;

use System\Controller;
use System\Http\Request;
use System\Http\RedirectResponse;

class Chat extends Controller{
    public function index(Request $request, array $args=[]) {
        if(!$request->data["session"]) return RedirectResponse::toRoute("login");
        return $this->renderTemplate("chat");
    }
}