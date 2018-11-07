<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Protocol;
use Codinghuang\SwFastDFSClient\Error;

class Tracker extends Base
{
    public function queryStorageWithGroup($groupName)
    {
        $reqHeader = self::buildHeader(
                Protocol::TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITH_GROUP_ONE, 
                Protocol::GROUP_NAME_MAX_LEN
        );
        $reqBody = self::padding($groupName, Protocol::GROUP_NAME_MAX_LEN);
        if ($this->send($reqHeader . $reqBody) === false) {
            return false;
        }
        $resHeader = $this->read(Protocol::HEADER_LENGTH);
        if ($reqHeader === false) {
            return false;
        }
        $resInfo = SELF::parseHeader($resHeader);
        if ($resInfo === false) {
            return false;
        }
        if ($resInfo['status'] !== 0) {
            Error::$errMsg = 'tracker server returned the wrong status value';
            return false;
        }

        $resBody = $this->read($resInfo['bodyLength']);
        $groupName = trim(substr($resBody, 0, Protocol::GROUP_NAME_MAX_LEN));
        $storageAddr = trim(substr($resBody, Protocol::GROUP_NAME_MAX_LEN, Protocol::IP_ADDRESS_MAX_LEN));
        var_dump($groupName);
        var_dump($storageAddr);
        exit;
    }
}