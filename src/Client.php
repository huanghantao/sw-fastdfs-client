<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Utils;

class Client
{
    const DEFAULT_HOST = "127.0.0.1";
    const DEFAULT_PORT = 22122;

    private $tracker;
    private $storage;
    private $config;

    public function __construct(array $config = null)
    {
        $this->config = $config;
        if (!isset($config) || !isset($config['host'])) {
            $this->config['host'] = SELF::DEFAULT_HOST;
        }
        if (!isset($config) || !isset($config['port'])) {
            $this->config['port'] = SELF::DEFAULT_PORT;
        }
        $this->tracker = new Tracker($this->config['host'], $this->config['port']);
    }

    public function connect()
    {
        return $this->tracker->connect();
    }

    public function uploadByFilename($pathToFile)
    {
        $storageInfo = $this->tracker->queryStorageWithGroup($this->config['group']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->uploadByFilename($storageInfo['storageIndex'], $pathToFile);
    }

    public function deleteFile($remoteFileId)
    {
        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->deleteFile($res['groupName'], $res['remoteFilename']);
    }

    public function uploadAppenderFile($pathToFile)
    {
        $storageInfo = $this->tracker->queryStorageWithGroup($this->config['group']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->uploadAppenderFile($storageInfo['storageIndex'], $pathToFile);
    }

    public function appendFile($content, $remoteFileId)
    {
        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->appendFile($content, $res['remoteFilename']);
    }

    public function readFile($remoteFileId, $offset = 0, $length = 0)
    {
        $res = Utils::splitRemoteFileId($remoteFileId);
        $storageInfo = $this->tracker->queryStorageUpdate($res['groupName'], $res['remoteFilename']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->readFile($res['groupName'], $res['remoteFilename'], $offset, $length);
    }
}