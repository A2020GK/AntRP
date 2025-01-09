<?php
namespace App\Modules;

use System\Module;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Controllers\Websocket as Chat;

class Websocket extends Module
{
    public function run()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8001
        );

        $server->run();
    }
}