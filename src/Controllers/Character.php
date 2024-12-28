<?php
namespace App\Controllers;

use System\Controller;
use System\Http\RedirectResponse;
use System\Http\Request;
use App\Models\Character as Model;
class Character extends Controller {
    public function list(Request $request, array $args=[]) {
        // if(!$request->data["session"]) return RedirectResponse::toRoute("user.login");
        // ^^^ This kills unwanted nigas who want to lurk something they're not prevented to
        return $this->renderTemplate("character.list", ["characters"=>Model::getAll()]);
    }
    public function view(Request $request, array $args=[]) {
        $ch = Model::getById(intval($args["id"]));
        if($ch) $ch->description = str_replace("\n","<br>", $ch->description);
        $session = $request->data["session"];
        $user=false;
        $canEdit=false;
        if($session && $ch) {
            $user = $session->user;
            if($user->isAdmin() or $ch->owner->username == $user->username) $canEdit=true;
        }
        return $this->renderTemplate("character.view", ["character"=>$ch,"canEdit"=>$canEdit]);
    }
    public function edit(Request $request, array $args=[]) {
        if(!$request->data["session"]) return RedirectResponse::toRoute("user.login");
        // ^^^ This kills unwanted nigas who want to lurk something they're not prevented to
        $ch = Model::getById(intval($args["id"]));
        $user=$request->data["session"]->user;
        $canEdit = false;
        if($ch) if($user->isAdmin() or $ch->owner->username == $ch->owner->username) {
            $canEdit=true;
        }
        if(!$canEdit && $ch) return RedirectResponse::toRoute("character.view",["id"=>$ch->id]);
        return $this->renderTemplate("character.edit",["character"=>$ch, "canEdit"=>$canEdit]);
    }
    public function handle_edit(Request $request, array $args=[]) {
        
    }
}