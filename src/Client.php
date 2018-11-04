<?php

namespace Codinghuang\SwFastDFSClient;

class Client
{
    const DEFAULT_HOST = "127.0.0.1";
    const DEFAULT_PORT = 22122;

    private $tracker;
    private $config;

    public function __construct(array $config = null)
    {
        $this->config = $config;
        if (!isset($config) || !isset($config['host'])) {
            $this->config['host'] = Client::DEFAULT_HOST;
        }
        if (!isset($config) || !isset($config['port'])) {
            $this->config['port'] = Client::DEFAULT_PORT;
        }
        $this->tracker = new Tracker($this->config['host'], $this->config['port']);
    }

    public function connect()
    {
        return $this->tracker->connect();
    }
}