<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Jungle_Browse_Statistics_WebSocket()
        )
    ),
    8080
);

$server->run();
