#!/usr/bin/env php
<?php

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Ratchet\Server\IoServer;

require __DIR__ . '/vendor/autoload.php';

$logger = new Logger('log');
$logger->pushHandler(new ErrorLogHandler());
$server = IoServer::factory(new TimerApplication($logger), 8080);
$server->run();
