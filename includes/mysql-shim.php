<?php

/**
 * mysql_* → mysqli (PHP 8+). Carregar apenas via bootstrap.php.
 */

if (defined('MYSQL_COMPAT_SHIM_LOADED')) {
    return;
}

define('MYSQL_COMPAT_SHIM_LOADED', true);

if (!function_exists('mysql_connect')) {
    $GLOBALS['__mysql_compat_link'] = null;

    function mysql_connect(
        $server = null,
        $username = null,
        $password = null,
        $new_link = false,
        $client_flags = 0
    ) {
        $host = $server ?? ini_get('mysqli.default_host');
        $port = ini_get('mysqli.default_port') ?: 3306;
        $socket = null;

        if (is_string($host) && str_contains($host, ':')) {
            [$host, $port] = explode(':', $host, 2);
            $port = (int) $port;
        }

        $link = mysqli_connect($host, $username, $password, '', (int) $port, $socket);
        if ($link === false) {
            return false;
        }

        $GLOBALS['__mysql_compat_link'] = $link;

        return $link;
    }

    function mysql_select_db($database_name, $link_identifier = null)
    {
        $link = $link_identifier ?: $GLOBALS['__mysql_compat_link'];

        return mysqli_select_db($link, $database_name);
    }

    function mysql_query($query, $link_identifier = null)
    {
        $link = $link_identifier ?: $GLOBALS['__mysql_compat_link'];

        return mysqli_query($link, $query);
    }

    function mysql_error($link_identifier = null)
    {
        $link = $link_identifier ?: $GLOBALS['__mysql_compat_link'];

        return mysqli_error($link);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH)
    {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_fetch_assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }

    function mysql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }

    function mysql_real_escape_string($unescaped_string, $link_identifier = null)
    {
        $link = $link_identifier ?: $GLOBALS['__mysql_compat_link'];

        return mysqli_real_escape_string($link, $unescaped_string);
    }

    function mysql_result($result, $row, $field = 0)
    {
        if (!mysqli_data_seek($result, $row)) {
            return false;
        }

        $data = mysqli_fetch_array($result);
        if ($data === null) {
            return false;
        }

        if (is_numeric($field)) {
            return $data[(int) $field];
        }

        return $data[$field];
    }
}
