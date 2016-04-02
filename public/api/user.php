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
echo json_encode($result);
die();