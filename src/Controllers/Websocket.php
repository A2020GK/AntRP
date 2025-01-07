<?php
namespace App\Controllers;

use Ratchet\MessageComponentInterface;
use App\Models\Session;
use Ratchet\ConnectionInterface;
use App\Models\Message;

class Websocket implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected function log(string $msg, string $prefix = "main")
    {
        echo "[AntRP_WS:$prefix] $msg\n";
    }
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->log("Start");
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->log("Got client {$conn->resourceId}", "clients");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg, true);
        $type = $msg["type"] ?? "ping";
        if ($type == "auth") {
            $token = $msg["token"] ?? false;
            if ($token) {
                $session = Session::getByToken($token);
                if ($session) {
                    $this->clients->offsetSet($from, $session);
                    $this->log("Client {$from->resourceId} authenticated with token {$session->token}; username = {$session->user->username}", "auth");
                    $from->send(json_encode([
                        "type" => "auth",
                        "ok" => true,
                        "user" => [
                            "username" => $session->user->username,
                            "name" => $session->user->name
                        ],
                    ]));
                    return;
                }
            }
            $this->log("Client auth failed :(", "errors");
            $from->send(json_encode([
                "type" => "auth",
                "ok" => false
            ]));
        } else if ($type == "message.send") {
            if ($sess = $this->clients->offsetGet($from)) {
                $user = $sess->user;
                $message = new Message(null, $user, $msg["text"]);
                $message->save();
                $this->log("Client {$from->resourceId} is sending message");

                foreach ($this->clients as $client) {
                    $client->send(json_encode([
                        "type"=>"message",
                        "text"=>$message->text,
                        "sender"=>$message->sender->username,
                        "time"=>date("H:i", $message->sent_at),
                    ]));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->log("closed client", "clients");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close(); // А ну не лезте ко мне в гроб >:(
        $this->log("Error somewhere, please check", "errors");
    }
}