<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Storage;
use Codinghuang\SwFastDFSClient\Tracker;

class TrackerPool
{
    const WORKER_NUM = 20;
    const TASK_WORKER_NUM = 15; // max connection num
    const DEFAULT_GROUP = 'wechat';
    const TRACKER_SERVER_HOST = '127.0.0.1';
    const TRACKER_SERVER_PORT = 22122;

    private $serv = null;
    private $logFile = '/tmp/tracker_pool.log';
    private $tracker = null;
    private $groupName;

    public function __construct($host, $port)
    {
        $this->serv = new \Swoole\Server($host, $port);
    }

    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
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

    public function connectTrackerServer()
    {
        $this->tracker = new Tracker(SELF::TRACKER_SERVER_HOST, SELF::TRACKER_SERVER_PORT);
        if (!$this->tracker->connect()) {
            return false;
        }

        return true;
    }

    public function start()
    {
        $this->init();
        if (!$this->connectTrackerServer()) {
            print_r(Error::$errMsg . PHP_EOL);
        }
        $this->serv->start();
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        $result = $serv->taskwait($data);
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        static $storage = null;
        if ($storage === null) {
            $storageInfo = $this->tracker->queryStorageWithGroup($this->groupName);
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            while (!$storage->connect()) {
                \Swoole\Coroutine::sleep(2);
            }
        }
    }

    public function onFinish($serv, $task_id, $data)
    {
        // do nothing
    }
}