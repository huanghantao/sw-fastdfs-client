<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Client;
use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Storage;
use Codinghuang\SwFastDFSClient\Tracker;

class RPCServer
{
    const WORKER_NUM = 20;
    const TASK_WORKER_NUM = 15; // max connection num
    const TRACKER_SERVER_HOST = '127.0.0.1';
    const TRACKER_SERVER_PORT = 22122;
    const DEFAULT_GROUP = 'wechat';

    static $client;

    private $serv = null;
    private $logFile = '/tmp/tracker_pool.log';

    public function __construct($host, $port)
    {
        $this->serv = new \Swoole\Server($host, $port);
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
        SELF::$client = new Client(SELF::TRACKER_SERVER_HOST, SELF::TRACKER_SERVER_PORT);
        if (!SELF::$client->connect()) {
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
        $res = $serv->taskwait($data);
        $serv->send($fd, $res);
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        $data = json_decode($data, true);
        $content = call_user_func_array([SELF::$client, $data['method']], $data['params']);

        if (!$content) {
            return Error::$errMsg . PHP_EOL;
        }

        $res = [
            'length' => strlen($content), 
            'content' => $content
        ];
        return json_encode($res);
    }

    public function onFinish($serv, $task_id, $data)
    {
        // do nothing
    }
}