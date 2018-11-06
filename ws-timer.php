#!/usr/bin/env php
<?php

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server;

require __DIR__ . '/vendor/autoload.php';

/*
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = () => console.log("Connection established!");
conn.onmessage = e => console.log(e.data);
*/


$logger = new Logger('log');
$logger->pushHandler(new ErrorLogHandler());
$logger->info('Server started');

$loop = LoopFactory::create();
$app = new TimerApplication($logger, $loop);
$socket = new Server('127.0.0.1:8080', $loop);
$server = new IoServer(new HttpServer(new WsServer($app)), $socket, $loop);
$server->run();
