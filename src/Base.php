<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Protocol;
use Codinghuang\SwFastDFSClient\Utils;

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
            Error::$errMsg = "[{$this->client->errCode}]: connect failed.";
            return null;
        }
        return $this->client;
    }

    public function send($data)
    {
        $res = $this->client->send($data);
        if ($res === false) {
            Error::$errMsg = "[{$this->client->errCode}]: send data failed.";
            return false;
        }
        return true;
    }

    public function read($length)
    {
        $data = $this->client->recv($length);
        if ($data === false) {
            Error::$errMsg = "[{$this->client->errCode}]: send data failed.";
            return false;
        }
        return $data;
    }

    public function sendfile($pathToFile)
    {
        $res = $this->client->sendfile($pathToFile);
        if ($res === false) {
            Error::$errMsg = "[{$this->client->errCode}]: send data failed.";
            return false;
        }
        return true;
    }
}