

## connection-pool

## Description

A fastdfs client base on swoole that supports connection pool

## Usage

### upload file

```php
<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

require '../vendor/autoload.php';

$client = new \Swoole\Client(SWOOLE_TCP);

if (!$client->connect('127.0.0.1', 9501))
{
    print_r('connect error' . PHP_EOL);
    exit;
}
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
```

## Reference

[EellyDev/fastdfs](https://github.com/EellyDev/fastdfs)

[weilaihui/fdfs_client](https://github.com/weilaihui/fdfs_client)

[happyfish100/fastdfs/client](https://github.com/happyfish100/fastdfs/tree/master/client)