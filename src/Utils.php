<?php

namespace Codinghuang\SwFastDFSClient;

use Codinghuang\SwFastDFSClient\Error;
use Codinghuang\SwFastDFSClient\Protocol;

class Utils
{
    public static function mineTypeExtension($mineType)
    {
        $types = [
            'application/andrew-inset'        => 'ez',
            'application/atom+xml'            => 'atom',
            'application/json'                => 'json',
            'application/mac-binhex40'        => 'hqx',
            'application/mac-compactpro'      => 'cpt',
            'application/mathml+xml'          => 'mathml',
            'application/msword'              => 'doc',
            'application/octet-stream'        => 'so',
            'application/oda'                 => 'oda',
            'application/ogg'                 => 'ogg',
            'application/pdf'                 => 'pdf',
            'application/postscript'          => 'ps',
            'application/rdf+xml'             => 'rdf',
            'application/rss+xml'             => 'rss',
            'application/smil'                => 'smil',
            'application/srgs'                => 'gram',
            'application/srgs+xml'            => 'grxml',
            'application/vnd.mif'             => 'mif',
            'application/vnd.mozilla.xul+xml' => 'xul',
            'application/vnd.ms-excel'        => 'xls',
            'application/vnd.ms-powerpoint'   => 'ppt',
            'application/vnd.rn-realmedia'    => 'rm',
            'application/vnd.wap.wbxml'       => 'wbxml',
            'application/vnd.wap.wmlc'        => 'wmlc',
            'application/vnd.wap.wmlscriptc'  => 'wmlsc',
            'application/voicexml+xml'        => 'vxml',
            'application/x-bcpio'             => 'bcpio',
            'application/x-cdlink'            => 'vcd',
            'application/x-chess-pgn'         => 'pgn',
            'application/x-cpio'              => 'cpio',
            'application/x-csh'               => 'csh',
            'application/x-director'          => 'dxr',
            'application/x-dvi'               => 'dvi',
            'application/x-futuresplash'      => 'spl',
            'application/x-gtar'              => 'gtar',
            'application/x-hdf'               => 'hdf',
            'application/x-javascript'        => 'js',
            'application/x-koan'              => 'skt',
            'application/x-latex'             => 'latex',
            'application/x-netcdf'            => 'nc',
            'application/x-sh'                => 'sh',
            'application/x-shar'              => 'shar',
            'application/x-shockwave-flash'   => 'swf',
            'application/x-stuffit'           => 'sit',
            'application/x-sv4cpio'           => 'sv4cpio',
            'application/x-sv4crc'            => 'sv4crc',
            'application/x-tar'               => 'tar',
            'application/x-tcl'               => 'tcl',
            'application/x-tex'               => 'tex',
            'application/x-texinfo'           => 'texinfo',
            'application/x-troff'             => 'tr',
            'application/x-troff-man'         => 'man',
            'application/x-troff-me'          => 'me',
            'application/x-troff-ms'          => 'ms',
            'application/x-ustar'             => 'ustar',
            'application/x-wais-source'       => 'src',
            'application/xhtml+xml'           => 'xhtml',
            'application/xml'                 => 'xsl',
            'application/xml-dtd'             => 'dtd',
            'application/xslt+xml'            => 'xslt',
            'application/zip'                 => 'zip',
            'audio/basic'                     => 'snd',
            'audio/midi'                      => 'midi',
            'audio/mpeg'                      => 'mpga',
            'audio/x-aiff'                    => 'aiff',
            'audio/x-mpegurl'                 => 'm3u',
            'audio/x-pn-realaudio'            => 'ram',
            'audio/x-wav'                     => 'wav',
            'chemical/x-pdb'                  => 'pdb',
            'chemical/x-xyz'                  => 'xyz',
            'image/bmp'                       => 'bmp',
            'image/cgm'                       => 'cgm',
            'image/gif'                       => 'gif',
            'image/ief'                       => 'ief',
            'image/jpeg'                      => 'jpg',
            'image/png'                       => 'png',
            'image/svg+xml'                   => 'svgz',
            'image/tiff'                      => 'tiff',
            'image/vnd.djvu'                  => 'djvu',
            'image/vnd.wap.wbmp'              => 'wbmp',
            'image/webp'                      => 'webp',
            'image/x-cmu-raster'              => 'ras',
            'image/x-icon'                    => 'ico',
            'image/x-portable-anymap'         => 'pnm',
            'image/x-portable-bitmap'         => 'pbm',
            'image/x-portable-graymap'        => 'pgm',
            'image/x-portable-pixmap'         => 'ppm',
            'image/x-rgb'                     => 'rgb',
            'image/x-xbitmap'                 => 'xbm',
            'image/x-xpixmap'                 => 'xpm',
            'image/x-xwindowdump'             => 'xwd',
            'model/iges'                      => 'igs',
            'model/mesh'                      => 'silo',
            'model/vrml'                      => 'wrl',
            'text/calendar'                   => 'ifb',
            'text/css'                        => 'css',
            'text/csv'                        => 'csv',
            'text/html'                       => 'html',
            'text/plain'                      => 'txt',
            'text/richtext'                   => 'rtx',
            'text/rtf'                        => 'rtf',
            'text/sgml'                       => 'sgml',
            'text/tab-separated-values'       => 'tsv',
            'text/vnd.wap.wml'                => 'wml',
            'text/vnd.wap.wmlscript'          => 'wmls',
            'text/x-setext'                   => 'etx',
            'video/mp4'                       => 'mp4',
            'video/mpeg'                      => 'mpg',
            'video/quicktime'                 => 'qt',
            'video/vnd.mpegurl'               => 'mxu',
            'video/x-msvideo'                 => 'avi',
            'video/x-sgi-movie'               => 'movie',
            'x-conference/x-cooltalk'         => 'ice',
        ];
        if (isset($types[$mineType])) {
            return $types[$mineType];
        } else {
            return '';
        }
    }

    public static function splitRemoteFileId($remoteFileId)
    {
        $tmp = explode('/', $remoteFileId, 2);
        if (count($tmp) < 2) {
            Error::$errMsg = 'Error remoteFileId';
            return false;
        }
        return [
            'groupName' => $tmp[0],
            'remoteFilename' => $tmp[1]
        ];
    }

    public static function buildHeader($protoCmd, $length = 0)
    {
        return Utils::packU64($length).pack('Cx', $protoCmd);
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

    public static function parseHeader($str)
    {
        if (strlen($str) !== Protocol::HEADER_LENGTH) {
            Error::$errMsg = "response header length error.";
            return false;
        }

        $result = unpack('C10', $str);

        $bodyLength = Utils::unpackU64(substr($str, 0, 8));
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