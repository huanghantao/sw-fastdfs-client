<?php

namespace Codinghuang\SwFastDFSClient;

class TrackerPool
{
    const WORKER_NUM = 100;
    const TASK_WORKER_NUM = 10;

    private $serv = null;
    private $host = '127.0.0.1';
    private $port = 9501;
    private $minConnectionNum = 100;
    private $maxConnectionNum = 150;
    private $logFile = '/tmp/tracker_pool.log';

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->serv = new \Swoole\Server($this->host, $this->port);
    }

    public function setMinConnectionNum($size)
    {
        $this->minConnectionNum = $size;
    }

    public function setMaxConnectionNum($size)
    {
        $this->_max_pool_size = $size;
        return $this;
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
        print_r($result);
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {
        print_r("Tasker进程接收到数据: {$data}");
        return "finish" . PHP_EOL;
    }

    public function onFinish($serv, $task_id, $data)
    {
        // do nothing
    }
}