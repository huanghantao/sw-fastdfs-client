<?php

use Codinghuang\SwFastDFSClient\TrackerPool;

require '../vendor/autoload.php';

$pool = new TrackerPool('127.0.0.1', 9501);
$pool->setGroupName('wechat');
$pool->start();
