<?php

namespace Jungle_Browse_Statistics;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/../vendor/autoload.php';

class Jungle_Browse_Statistics_WebSocket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // In this example, when we receive a message from a client, we assume it's a heartbeat
        // message and we return a heartbeat response
        if ($msg === 'heartbeat') {
            $from->send('heartbeat');
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
