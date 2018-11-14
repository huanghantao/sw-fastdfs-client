<?php

namespace Codinghuang\SwFastDFSClient;

class Protocol
{
    const GROUP_NAME_MAX_LEN = 16;
    const HEADER_LENGTH = 10;
    const IP_ADDRESS_LEN = 15;
    const PROTO_PKG_LEN = 8;
    const STORE_PATH_INDEX = 1;
    const FDFS_FILE_EXT_NAME_MAX_LEN = 6;

    const FDFS_PROTO_CMD_QUIT = 82;

    const TRACKER_PROTO_CMD_STORAGE_JOIN = 81;
    const TRACKER_PROTO_CMD_STORAGE_BEAT = 83; // storage heart beat
    const TRACKER_PROTO_CMD_STORAGE_REPORT_DISK_USAGE = 84; // report disk usage
    const TRACKER_PROTO_CMD_STORAGE_REPLICA_CHG = 85; // repl new storage servers
    const TRACKER_PROTO_CMD_STORAGE_SYNC_SRC_REQ = 86; // src storage require sync
    const TRACKER_PROTO_CMD_STORAGE_SYNC_DEST_REQ = 87; // dest storage require sync
    const TRACKER_PROTO_CMD_STORAGE_SYNC_NOTIFY = 88; // sync done notify
    const TRACKER_PROTO_CMD_STORAGE_SYNC_REPORT = 89; // report src last synced time as dest server
    const TRACKER_PROTO_CMD_STORAGE_SYNC_DEST_QUERY = 79; // dest storage query sync src storage server
    const TRACKER_PROTO_CMD_STORAGE_REPORT_IP_CHANGED = 78; // storage server report it's ip changed
    const TRACKER_PROTO_CMD_STORAGE_CHANGELOG_REQ = 77; // storage server request storage server's changelog
    const TRACKER_PROTO_CMD_STORAGE_REPORT_STATUS = 76; // report specified storage server status
    const TRACKER_PROTO_CMD_STORAGE_PARAMETER_REQ = 75; // storage server request parameters
    const TRACKER_PROTO_CMD_STORAGE_REPORT_TRUNK_FREE = 74; // storage report trunk free space
    const TRACKER_PROTO_CMD_STORAGE_REPORT_TRUNK_FID = 73; // storage report current trunk file id
    const TRACKER_PROTO_CMD_STORAGE_FETCH_TRUNK_FID = 72; // storage get current trunk file id
    const TRACKER_PROTO_CMD_TRACKER_GET_SYS_FILES_START = 61; // start of tracker get system data files
    const TRACKER_PROTO_CMD_TRACKER_GET_SYS_FILES_END = 62; // end of tracker get system data files
    const TRACKER_PROTO_CMD_TRACKER_GET_ONE_SYS_FILE = 63; // tracker get a system data file
    const TRACKER_PROTO_CMD_TRACKER_GET_STATUS = 64; // tracker get status of other tracker
    const TRACKER_PROTO_CMD_TRACKER_PING_LEADER = 65; // tracker ping leader
    const TRACKER_PROTO_CMD_TRACKER_NOTIFY_NEXT_LEADER = 66; // notify next leader to other trackers
    const TRACKER_PROTO_CMD_TRACKER_COMMIT_NEXT_LEADER = 67; // commit next leader to other trackers
    const TRACKER_PROTO_CMD_SERVER_LIST_ONE_GROUP = 90;
    const TRACKER_PROTO_CMD_SERVER_LIST_ALL_GROUPS = 91;
    const TRACKER_PROTO_CMD_SERVER_LIST_STORAGE = 92;
    const TRACKER_PROTO_CMD_SERVER_DELETE_STORAGE = 93;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITHOUT_GROUP_ONE = 101;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_FETCH_ONE = 102;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_UPDATE = 103;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITH_GROUP_ONE = 104;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_FETCH_ALL = 105;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITHOUT_GROUP_ALL = 106;
    const TRACKER_PROTO_CMD_SERVICE_QUERY_STORE_WITH_GROUP_ALL = 107;
    const TRACKER_PROTO_CMD_RESP = 100;

    const STORAGE_PROTO_CMD_UPLOAD_FILE = 11;
    const STORAGE_PROTO_CMD_DELETE_FILE = 12;
    const STORAGE_PROTO_CMD_DOWNLOAD_FILE = 14;
    const STORAGE_PROTO_CMD_UPLOAD_APPENDER_FILE = 23; //create appender file
    const STORAGE_PROTO_CMD_APPEND_FILE = 24;
}