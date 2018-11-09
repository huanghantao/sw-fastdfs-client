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

    public function uploadFile($pathToFile)
    {
        $storageInfo = $this->tracker->queryStorageWithGroup($this->config['group']);
        if ($storageInfo === false) {
            return false;
        }
        $this->storage = new Storage($storageInfo['storageAddr'], $storageInfo['storagePort']);
        if (!$this->storage->connect()) {
            return false;
        }
        return $this->storage->uploadFile($storageInfo['storageIndex'], $pathToFile);
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
}