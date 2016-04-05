<?php
function getRootDirectory()
{
    return "/BlankSpace";
}

function getProjectIni()
{
    $default_ini = parse_ini_file(getRootDirectory() . "/conf.ini", true);
    $private_ini = file_exists(getRootDirectory() . "/private.ini") ? parse_ini_file(getRootDirectory() . "/private.ini", true) : array();
    return array_merge($default_ini, $private_ini);
}

function send_headers($type = null)
{
    switch ($type) {
        default:
            header("Content-Type: application/json");
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Access-Control-Max-Age: 1000');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            break;
    }
}

function valid_phone($phone)
{
    return strlen($phone) == 10 && is_numeric($phone) && $phone > 0;
}
