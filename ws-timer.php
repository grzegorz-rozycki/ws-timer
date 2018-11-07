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

// JavaScript client code
/*
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = () => console.log('Connection established!');
conn.onmessage = e => console.log(e.data);
*/
$handler = ! getenv('APP_DEBUG')
    ? $handler = new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::WARNING)
    : $handler = new ErrorLogHandler();
$host = getenv('APP_HOST') ?: '0.0.0.0';
$port = getenv('APP_PORT') ?: '8080';

$logger = new Logger('log');
$logger->pushHandler($handler);
$logger->info('Server started');

$loop = LoopFactory::create();
$app = new TimerApplication($logger, $loop);
$socket = new Server("{$host}:{$port}", $loop);
$server = new IoServer(new HttpServer(new WsServer($app)), $socket, $loop);
$server->run();
