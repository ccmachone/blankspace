<?php
include_once("../../classes.php");
$handler = new \User_HANDLER();
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $result = $handler->handleGet($_GET);
        break;
    case "POST":
        $result = $handler->handlePost($_POST);
        break;
    default:
        throw new \Exception("Method " . $_SERVER['REQUEST_METHOD'] . " is not implemented for User");
        break;
}
header("Content-type: application/json");
header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

echo json_encode($result);
die();