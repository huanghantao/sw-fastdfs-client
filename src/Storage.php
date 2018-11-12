<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Protocol;
use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Buffer;
use Codinghuang\SwFastDFSClient\Utils;

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
            $ext = $fileInfo['extension'] ?? Utils::mineTypeExtension(mime_content_type($filename));
        }

        $fp = fopen($filename, 'rb');
        $fileSize = filesize($filename);
        $requestBodyLength = 1 + Protocol::PROTO_PKG_LEN + 
                Protocol::FDFS_FILE_EXT_NAME_MAX_LEN + $fileSize;

        $requestHeader = Utils::buildHeader(Protocol::STORAGE_PROTO_CMD_UPLOAD_FILE, $requestBodyLength);
        $requestBody = pack('C', $storageIndex).Utils::packU64($fileSize).Utils::padding($ext, Protocol::FDFS_FILE_EXT_NAME_MAX_LEN);
        if ($this->send($requestHeader . $requestBody) === false) {
            return false;
        }
        if ($this->sendfile($filename) === false) {
            return false;
        }
        $responseHeader = $this->read(Protocol::HEADER_LENGTH);
        $responseInfo = Utils::parseHeader($responseHeader);
        if ($responseInfo['status'] !== 0) {
            Error::$errMsg = "Error: receive response status code {$responseInfo['status']}";
            return false;
        }
        $responseBody = $this->read($responseInfo['bodyLength']);
        $buffer = new Buffer();
        $buffer->writeToBuffer($responseBody, $responseInfo['bodyLength']);
        $groupName = trim($buffer->readFromBuffer(Protocol::GROUP_NAME_MAX_LEN));
        $remoteFilename = trim($buffer->readFromBuffer($responseInfo['bodyLength'] - Protocol::GROUP_NAME_MAX_LEN));
        $remoteFileId = $groupName . '/' . $remoteFilename;
        return $remoteFileId;
    }

    public function deleteFile($groupName, $remoteFilename)
    {
        $remoteFilenameLen = strlen($remoteFilename);
        $requestBodyLength = Protocol::GROUP_NAME_MAX_LEN + $remoteFilenameLen;
        $requestHeader = Utils::buildHeader(Protocol::STORAGE_PROTO_CMD_DELETE_FILE, $requestBodyLength);
        $requestBody = Utils::padding($groupName, Protocol::GROUP_NAME_MAX_LEN);
        $requestBody .= Utils::padding($remoteFilename, $remoteFilenameLen);
        if ($this->send($requestHeader . $requestBody) === false) {
            return false;
        }
        $responseHeader = $this->read(Protocol::HEADER_LENGTH);
        $responseInfo = Utils::parseHeader($responseHeader);
        if ($responseInfo['status'] !== 0) {
            Error::$errMsg = "Error: receive response status code {$responseInfo['status']}";
            return false;
        }
        return true;
    }

    public function appendFile($content, $remoteFilename)
    {
        $remoteFilenameLen = strlen($remoteFilename);
        $contentLen = strlen($content);
        $requestBodyLength = (2 * Protocol::PROTO_PKG_LEN) + $remoteFilenameLen + $contentLen;
        $requestHeader = Utils::buildHeader(Protocol::STORAGE_PROTO_CMD_APPEND_FILE, $requestBodyLength);
        $requestBody = pack('x4N', $remoteFilenameLen).self::packU64($contentLen).$remoteFilename.$content;
        $this->send($requestHeader . $requestBody);
    }
}