<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

require '../vendor/autoload.php';

// $config = [
//     'host' => '127.0.0.1',
//     'port' => 22122
// ];
$client = new Client();
if (!$client->connect()) {
    print_r(Error::$errMsg . PHP_EOL);
}