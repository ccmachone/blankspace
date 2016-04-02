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
