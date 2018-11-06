<?php

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class TimerApplication implements MessageComponentInterface
{
    protected $connections;

    protected $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->connections = new SplObjectStorage();
        $this->logger = $logger;
        $this->logger->info('Application ready to handle connections');
    }

    /** @inheritdoc */
    function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
        $this->logger->info("New connection {$conn->resourceId}");
    }

    /** @inheritdoc */
    function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
        $this->logger->info("Connection {$conn->resourceId} closed");
    }

    /** @inheritdoc */
    function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->logger->info("Connection {$conn->resourceId} encountered an error {$e->getMessage()}");
        $conn->close();
    }

    /** @inheritdoc */
    function onMessage(ConnectionInterface $from, $msg)
    {

    }
}
