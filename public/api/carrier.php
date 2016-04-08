<?php
include_once("../../classes.php");
$handler = new \Carrier_HANDLER();
switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        $result = $handler->handleGet($_GET);
        break;
    default:
        throw new \Exception("Method " . $_SERVER['REQUEST_METHOD'] . " is not implemented for Sentiment");
        break;
}
send_headers();
echo json_encode($result);
die();