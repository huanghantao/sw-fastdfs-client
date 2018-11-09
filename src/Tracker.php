<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Protocol;
use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Buffer;
use Codinghuang\SwFastDFSClient\Utils;

class Tracker extends Base
{
    public function queryStorageWithGroup($groupName)
    {
        $reqHeader = Utils::buildHeader(
                Protocol::TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITH_GROUP_ONE, 
                Protocol::GROUP_NAME_MAX_LEN
        );
        $reqBody = Utils::padding($groupName, Protocol::GROUP_NAME_MAX_LEN);
        if ($this->send($reqHeader . $reqBody) === false) {
            return false;
        }
        $resHeader = $this->read(Protocol::HEADER_LENGTH);
        if ($reqHeader === false) {
            return false;
        }
        $resInfo = Utils::parseHeader($resHeader);
        if ($resInfo === false) {
            return false;
        }
        if ($resInfo['status'] !== 0) {
            Error::$errMsg = "Error: receive response status code {$resInfo['status']}";
            return false;
        }

        // response format |groupName(16)+ipAddr(15)+port(8)+storePathIndex(1)|
        $resBody = $this->read($resInfo['bodyLength']);
        $buffer = new Buffer();
        $buffer->writeToBuffer($resBody, $resInfo['bodyLength']);
        $groupName = trim($buffer->readFromBuffer(Protocol::GROUP_NAME_MAX_LEN));
        $storageAddr = trim($buffer->readFromBuffer(Protocol::IP_ADDRESS_LEN));
        $storagePort = $buffer->unpackFromBuffer('N2', Protocol::PROTO_PKG_LEN)[2];
        $storageIndex = ord($buffer->readFromBuffer(Protocol::STORE_PATH_INDEX));
        return [
            'groupName' => $groupName,
            'storageAddr' => $storageAddr,
            'storagePort' => $storagePort,
            'storageIndex' => $storageIndex
        ];
    }

    public function queryStorageUpdate($groupName, $remoteFilename)
    {
        return $this->queryStorage($groupName, $remoteFilename, Protocol::TRACKER_PROTO_CMD_SERVICE_QUERY_UPDATE);
    }

    public function queryStorage($groupName, $remoteFilename, $protoCmd)
    {
        $pkgLen = Protocol::GROUP_NAME_MAX_LEN + strlen($remoteFilename);
        $reqHeader = Utils::buildHeader(
            $protoCmd, 
            Protocol::GROUP_NAME_MAX_LEN + strlen($remoteFilename)
        );
        $reqBody = Utils::padding($groupName, Protocol::GROUP_NAME_MAX_LEN);
        $reqBody .= Utils::padding($remoteFilename, strlen($remoteFilename));

        if ($this->send($reqHeader . $reqBody) === false) {
            return false;
        }
        $resHeader = $this->read(Protocol::HEADER_LENGTH);
        if ($reqHeader === false) {
            return false;
        }
        $resInfo = Utils::parseHeader($resHeader);
        if ($resInfo === false) {
            return false;
        }
        if ($resInfo['status'] !== 0) {
            Error::$errMsg = "Error: receive response status code {$resInfo['status']}";
            return false;
        }
        $resBody = $this->read($resInfo['bodyLength']);
        $buffer = new Buffer();
        $buffer->writeToBuffer($resBody, $resInfo['bodyLength']);
        $groupName = trim($buffer->readFromBuffer(Protocol::GROUP_NAME_MAX_LEN));
        $storageAddr = trim($buffer->readFromBuffer(Protocol::IP_ADDRESS_LEN));
        $storagePort = $buffer->unpackFromBuffer('N2', Protocol::PROTO_PKG_LEN)[2];
        $storageIndex = ord($buffer->readFromBuffer(Protocol::STORE_PATH_INDEX));
        return [
            'groupName' => $groupName,
            'storageAddr' => $storageAddr,
            'storagePort' => $storagePort,
            'storageIndex' => $storageIndex
        ];
    }
}