<?php

use Codinghuang\SwFastDFSClient\RPCServer;

require '../vendor/autoload.php';

$pool = new RPCServer('127.0.0.1', 9501);
$pool->start();
