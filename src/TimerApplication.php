<?php

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;

class TimerApplication implements MessageComponentInterface
{
    public const TICK_INTERVAL = 1;

    protected $connections;

    protected $logger;

    protected $loop;

    protected $timer;

    public function __construct(LoggerInterface $logger, LoopInterface $loop)
    {
        $this->connections = new SplObjectStorage();
        $this->logger = $logger;
        $this->loop = $loop;
        $this->timer = $loop->addPeriodicTimer(self::TICK_INTERVAL, [$this, 'handleTick']);
    }

    public function __destruct()
    {
        $this->loop->cancelTimer($this->timer);
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

    function handleTick()
    {
        $now = date(DATE_W3C);
        $this->logger->debug($now);

        /** @var ConnectionInterface $connection */
        foreach ($this->connections as $connection) {
            $connection->send($now);
        }
    }
}
