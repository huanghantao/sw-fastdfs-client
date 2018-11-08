

## Description

a fastdfs client base on swoole

## Install

```shell
composer require huanghantao/sw-fastfdfs-client
```

## Usage

### uploadfile

```php
<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

$config = [
    'host' => '127.0.0.1',
    'port' => 22122,
    'group' => 'wechat'
];
$client = new Client($config);
if (!$client->connect()) {
    print_r(Error::$errMsg . PHP_EOL);
    // Some code that handles errors
}

$res = $client->uploadFile('test.txt');
if (!$res) {
    print_r(Error::$errMsg . PHP_EOL);
    // Some code that handles errors
}

print_r($res);
```

## Reference

[EellyDev/fastdfs](https://github.com/EellyDev/fastdfs)

[weilaihui/fdfs_client](https://github.com/weilaihui/fdfs_client)