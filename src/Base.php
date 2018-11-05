<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Protocol;

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
        return $data;
    }

    public static function parseHeader($str)
    {
        if (strlen($str) !== Protocol::HEADER_LENGTH) {
            Error::$errMsg = "response header length error.";
            return false;
        }

        $result = unpack('C10', $str);

        $bodyLength = self::unpackU64(substr($str, 0, 8));
        $command = $result[9];
        $status = $result[10];

        return [
            'bodyLength'  => $bodyLength,
            'command' => $command,
            'status'  => $status,
        ];
    }

    public static function unpackU64($v)
    {
        list($hi, $lo) = array_values(unpack('N*N*', $v));

        if (PHP_INT_SIZE >= 8) {
            if ($hi < 0) {
                $hi += (1 << 32);
            } // because php 5.2.2 to 5.2.5 is totally fucked up again
            if ($lo < 0) {
                $lo += (1 << 32);
            }

            // x64, int
            if ($hi <= 2147483647) {
                return ($hi << 32) + $lo;
            }

            // x64, bcmath
            if (function_exists('bcmul')) {
                return bcadd($lo, bcmul($hi, '4294967296'));
            }

            // x64, no-bcmath
            $C = 100000;
            $h = ((int) ($hi / $C) << 32) + (int) ($lo / $C);
            $l = (($hi % $C) << 32) + ($lo % $C);
            if ($l > $C) {
                $h += (int) ($l / $C);
                $l = $l % $C;
            }

            if (0 == $h) {
                return $l;
            }

            return sprintf('%d%05d', $h, $l);
        }

        // x32, int
        if (0 == $hi) {
            if ($lo > 0) {
                return $lo;
            }

            return sprintf('%u', $lo);
        }

        $hi = sprintf('%u', $hi);
        $lo = sprintf('%u', $lo);

        // x32, bcmath
        if (function_exists('bcmul')) {
            return bcadd($lo, bcmul($hi, '4294967296'));
        }

        // x32, no-bcmath
        $hi = (float) $hi;
        $lo = (float) $lo;

        $q = floor($hi / 10000000.0);
        $r = $hi - $q * 10000000.0;
        $m = $lo + $r * 4967296.0;
        $mq = floor($m / 10000000.0);
        $l = $m - $mq * 10000000.0;
        $h = $q * 4294967296.0 + $r * 429.0 + $mq;

        $h = sprintf('%.0f', $h);
        $l = sprintf('%07.0f', $l);
        if ('0' == $h) {
            return sprintf('%.0f', (float) $l);
        }

        return $h.$l;
    }
}