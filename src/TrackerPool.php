<?php

namespace Codinghuang\SwFastDFSClient;

class TrackerPool
{
    const WORKER_NUM = 100;
    const TASK_WORKER_NUM = 10;

    private $serv = null;

    public function __construct(array $config = null)
    {
        $this->serv = new \Swoole\Server("127.0.0.1", 9501);
    }

    public function init()
    {
        $this->serv->set([
            'worker_num' => 100,
            'task_worker_num' => 10,
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
        return "finish";
    }

    public function onFinish($serv, $task_id, $data)
    {
        // do nothing
    }
}