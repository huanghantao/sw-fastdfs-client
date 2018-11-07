<?php

namespace Codinghuang\SwFastDFSClient;

class Buffer
{
    private $buffer;
    private $position;

    public function __construct()
    {
        $this->position = 0;
    }

    public function writeToBuffer($buffer)
    {
        $this->buffer = $buffer;
        $this->position = 0;
    }

    public function readFromBuffer($len)
    {
        $res = substr($this->buffer, $this->position, $len);
        $this->position += $len;
        return $res;
    }

    public function unpackFromBuffer($format, $len)
    {
        $res = unpack($format, substr($this->buffer, $this->position, $len));
        $this->position += $len;
        return $res;
    }
}