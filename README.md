

## Description

a fastdfs client base on swoole

## Install

```shell
composer require huanghantao/sw-fastfdfs-client
```

## Usage

### upload file

```php
<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

/* Tracker server configuration */
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

$remoteFileId = $client->uploadFile('test.txt');
if (!$remoteFileId) {
    print_r(Error::$errMsg . PHP_EOL);
    // Some code that handles errors
}
/* RemoteFileId is similar to wechat/M00/00/00/wKhgAlvlKAKAaVwdAAAACzYHTOE508.txt */
print_r($remoteFileId);
```

### delete file

```php
<?php

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

require '../vendor/autoload.php';

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

$remoteFileId = $client->uploadFile('test.txt');
if (!$remoteFileId) {
    print_r(Error::$errMsg . PHP_EOL);
    // Some code that handles errors
}
$res = $client->deleteFile($remoteFileId);
if (!$res) {
    print_r(Error::$errMsg . PHP_EOL);
    // Some code that handles errors
}
print_r('删除文件成功' . PHP_EOL);
```

## Reference

[EellyDev/fastdfs](https://github.com/EellyDev/fastdfs)

[weilaihui/fdfs_client](https://github.com/weilaihui/fdfs_client)