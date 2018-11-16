<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

require '../vendor/autoload.php';

$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if (!$client->connect('127.0.0.1', 9501))
{
    print_r('connect error' . PHP_EOL);
    exit;
}
//向服务器发送数据
$data = [
    'method' => 'setGroupName',
    'params' => [
        'wechat'
    ]
];
if (!$client->send(json_encode($data))) {
    print_r('send error' . PHP_EOL);
    exit;
}

$res = $client->recv(1024);
print_r($res . PHP_EOL);

$data = [
    'method' => 'uploadByFilename',
    'params' => [
        'test.txt'
    ]
];
if (!$client->send(json_encode($data))) {
    print_r('send error' . PHP_EOL);
    exit;
}

$res = $client->recv(1024);
print_r($res . PHP_EOL);