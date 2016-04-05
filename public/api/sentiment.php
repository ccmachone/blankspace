<?php
include_once("../../classes.php");
$handler = new \Sentiment_HANDLER();
switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $result = $handler->handlePost($_POST);
        break;
    default:
        throw new \Exception("Method " . $_SERVER['REQUEST_METHOD'] . " is not implemented for Sentiment");
        break;
}
send_headers();
echo json_encode($result);
die();