<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Error;

class Base
{
    protected $host;
    protected $port;
    protected $client;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function connect()
    {
        $this->client = new \Swoole\Client(SWOOLE_TCP);
        if (!$this->client->connect($this->host, $this->port, -1)) {
            Error::$errMsg = "[{$this->client->errCode}]: connect failed. ";
            return null;
        }
        return $this->client;
    }
}