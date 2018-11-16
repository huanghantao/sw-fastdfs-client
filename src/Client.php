<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Utils;

class Client
{
    const DEFAULT_HOST = "127.0.0.1";
    const DEFAULT_PORT = 22122;

    private $tracker;
    private $host;
    private $port;
    private $table;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->tracker = new Tracker($host, $port);
        $table = new \Swoole\Table(256);
        $table->column('groupName', \Swoole\Table::TYPE_STRING, 64);
        $table->create();
        $this->table = $table;
    }

    public function connect()
    {
        return $this->tracker->connect();
    }

    public function setGroupName($groupName)
    {
        $this->table->set('1', ['groupName' => $groupName]);
        return true;
    }

    public function getGroupName()
    {
        $res = $this->table->get('1');
        return $res['groupName'];
    }

    public function uploadByFilename($pathToFile)
    {
        static $storage = null;

        $storageInfo = $this->tracker->queryStorageWithGroup($this->getGroupName());
        if ($storageInfo === false) {
            return false;
        }
        if ($storage === null) {
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            if (!$storage->connect()) {
                return false;
            }
        }
        
        return $storage->uploadByFilename($storageInfo['storageIndex'], $pathToFile);
    }

    public function deleteFile($remoteFileId)
    {
        static $storage = null;

        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        if ($storage === null) {
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            if (!$storage->connect()) {
                return false;
            }
        }
        return $storage->deleteFile($res['groupName'], $res['remoteFilename']);
    }

    public function uploadAppenderFile($pathToFile)
    {
        static $storage = null;

        $storageInfo = $this->tracker->queryStorageWithGroup($this->getGroupName());
        if ($storageInfo === false) {
            return false;
        }
        if ($storage === null) {
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            if (!$storage->connect()) {
                return false;
            }
        }
        return $storage->uploadAppenderFile($storageInfo['storageIndex'], $pathToFile);
    }

    public function appendFile($content, $remoteFileId)
    {
        static $storage = null;

        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        if ($storage === null) {
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            if (!$storage->connect()) {
                return false;
            }
        }
        return $storage->appendFile($content, $res['remoteFilename']);
    }

    public function readFile($remoteFileId, $offset = 0, $length = 0)
    {
        static $storage = null;

        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        if ($storage === null) {
            $storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
            if (!$storage->connect()) {
                return false;
            }
        }
        return $storage->readFile($res['groupName'], $res['remoteFilename'], $offset, $length);
    }
}