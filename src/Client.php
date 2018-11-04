<?php

namespace Codinghuang\SwFastDFSClient;

class Client
{
    private $tracker;
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        if (!isset($config['host'])) {
            $this->config['host'] = "127.0.0.1";
        }
        if (!isset($config['port'])) {
            $this->config['port'] = 22122;
        }
        $this->tracker = new Tracker($this->config['host'], $this->config['port']);
    }

    public function connect()
    {
        return $this->tracker->connect();
    }
}