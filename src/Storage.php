<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Protocol;
use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Buffer;
use Codinghuang\SwFastDFSClient\Common;

class Storage extends Base
{
    public function uploadFile($storageIndex, $filename, $ext = '')
    {
        if (!file_exists($filename)) {
            Error::$errMsg = 'The file that needs to be uploaded does not exist';
            return false;
        }
        $fileInfo = pathinfo($filename);
        if (strlen($ext) > Protocol::FDFS_FILE_EXT_NAME_MAX_LEN) {
            Error::$errMsg = 'File extension is too long';
            return false;
        }
        if ($ext === '') {
            $ext = $fileInfo['extension'] ?? Common::mineTypeExtension(mime_content_type($filename));
        }

        $fp = fopen($filename, 'rb');
        $fileSize = filesize($filename);
        $requestBodyLength = 1 + Protocol::PROTO_PKG_LEN + 
                Protocol::FDFS_FILE_EXT_NAME_MAX_LEN + $fileSize;

        $requestHeader = self::buildHeader(Protocol::STORAGE_PROTO_CMD_UPLOAD_FILE, $requestBodyLength);
        $requestBody = pack('C', $storageIndex).self::packU64($fileSize).self::padding($ext, Protocol::FDFS_FILE_EXT_NAME_MAX_LEN);
        if ($this->send($requestHeader . $requestBody) === false) {
            return false;
        }
        $res = $this->sendfile($filename);
        if ($res === false) {
            return false;
        }
        $responseHeader = $this->read(Protocol::HEADER_LENGTH);
        $responseInfo = self::parseHeader($responseHeader);
        if ($responseInfo['status'] !== 0) {
            Error::$errMsg = "receive header error {$resInfo['status']}";
            return false;
        }
        $responseBody = $this->read($responseInfo['bodyLength']);
        $buffer = new Buffer();
        $buffer->writeToBuffer($responseBody, $responseInfo['bodyLength']);
        $groupName = trim($buffer->readFromBuffer(Protocol::GROUP_NAME_MAX_LEN));
        $filePath = trim($buffer->readFromBuffer($responseInfo['bodyLength'] - Protocol::GROUP_NAME_MAX_LEN));
        return [
            'groupName' => $groupName,
            'filePath'  => $filePath,
        ];
    }
}