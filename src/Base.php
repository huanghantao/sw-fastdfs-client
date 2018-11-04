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
            Error::$errMsg = "[{$this->client->errCode}]: connect failed.";
            return null;
        }
        return $this->client;
    }

    public static function buildHeader($protoCmd, $length = 0)
    {
        return self::packU64($length).pack('Cx', $protoCmd);
    }

    
    public static function packU64($v)
    {
        assert(is_numeric($v));

        // x64
        if (PHP_INT_SIZE >= 8) {
            assert($v >= 0);

            // x64, int
            if (is_int($v)) {
                return pack('NN', $v >> 32, $v & 0xFFFFFFFF);
            }

            // x64, bcmath
            if (function_exists('bcmul')) {
                $h = bcdiv($v, 4294967296, 0);
                $l = bcmod($v, 4294967296);

                return pack('NN', $h, $l);
            }

            // x64, no-bcmath
            $p = max(0, strlen($v) - 13);
            $lo = (int) substr($v, $p);
            $hi = (int) substr($v, 0, $p);

            $m = $lo + $hi * 1316134912;
            $l = $m % 4294967296;
            $h = $hi * 2328 + (int) ($m / 4294967296);

            return pack('NN', $h, $l);
        }

        // x32, int
        if (is_int($v)) {
            return pack('NN', 0, $v);
        }

        // x32, bcmath
        if (function_exists('bcmul')) {
            $h = bcdiv($v, '4294967296', 0);
            $l = bcmod($v, '4294967296');

            return pack('NN', (float) $h, (float) $l); // conversion to float is intentional; int would lose 31st bit
        }

        // x32, no-bcmath
        $p = max(0, strlen($v) - 13);
        $lo = (float) substr($v, $p);
        $hi = (float) substr($v, 0, $p);

        $m = $lo + $hi * 1316134912.0;
        $q = floor($m / 4294967296.0);
        $l = $m - ($q * 4294967296.0);
        $h = $hi * 2328.0 + $q;

        return pack('NN', $h, $l);
    }

    public static function padding($str, $len)
    {
        $str_len = strlen($str);

        return $str_len > $len
            ? substr($str, 0, $len)
            : $str.pack('x'.($len - $str_len));
    }

    public function send($data)
    {
        var_dump($data);
        $res = $this->client->send($data);
        if ($res === false) {
            Error::$errMsg = "[{$this->client->errCode}]: send data failed.";;
            return false;
        }
        return true;
    }
}