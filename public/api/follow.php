<?php
include_once("../../classes.php");
$handler = new \Follow_HANDLER();
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $result = $handler->handleGet($_GET);
        break;
    case "POST":
        $result = $handler->handlePost($_POST);
        break;
    default:
        throw new \Exception("Method " . $_SERVER['REQUEST_METHOD'] . " is not implemented for Follow");
        break;
}
send_headers();
echo json_encode($result);
die();