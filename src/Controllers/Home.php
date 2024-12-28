<?php
namespace App\Controllers;

use System\Http\Request;
use App\Models\Character;
class Home extends \System\Controller
{
    public function index(Request $request, array $args = [])
    {
        $session = $request->data["session"];
        if($session) {
            $user = $session->user;
            $characters = Character::getByUser($user);
        }
        return $this->renderTemplate("home",["user"=>$user??false,"characters"=>$characters??false]);
    }
}