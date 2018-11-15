<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;

class TrackerPool
{
    const WORKER_NUM = 20;
    const TASK_WORKER_NUM = 15; // max connection num
    const DEFAULT_GROUP = 'wechat';
    const TRACKER_SERVER_HOST = '127.0.0.1';
    const TRACKER_SERVER_PORT = 22122;

    private $serv = null;
    private $logFile = '/tmp/tracker_pool.log';

    public function __construct($host, $port)
    {
        $this->serv = new \Swoole\Server($host, $port);
    }

    public function setMinConnectionNum($size)
    {
        $this->minConnectionNum = $size;
    }

    public function setMaxConnectionNum($size)
    {
        $this->_max_pool_size = $size;
    }

    public function setLogFile($pathToFile)
    {
        $this->logFile = $pathToFile;
    }

    public function init()
    {
        $this->serv->set([
            'worker_num' => SELF::WORKER_NUM,
            'task_worker_num' => SELF::TASK_WORKER_NUM,
            'log_file' => $this->logFile,
        ]);

        // $this->serv->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->serv->on('Receive', [$this, 'onReceive']);
        $this->serv->on('Task', [$this, 'onTask']);
        $this->serv->on('Finish', [$this, 'onFinish']);
    }

    public function start()
    {
        $this->init();
        $this->serv->start();
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        print_r('Receive: ' . $data);
        $result = $serv->taskwait($data);
        $serv->send($fd, $result);
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        static $client = null;
        static $config = [
            'host' => SELF::TRACKER_SERVER_HOST,
            'port' => SELF::TRACKER_SERVER_PORT,
            'group' => SELF::DEFAULT_GROUP,
        ];

        if ($client == null) {
            $client = new Client($config);
            $client->connect();
        }

        $res = $client->uploadByFilename('test.txt');
        return $res;
    }

    public function onFinish($serv, $task_id, $data)
    {
        // do nothing
    }
}