<?php
include_once("DA.php");

class Checkin_DA extends \DA {
    public function getAllInLastHour()
    {
        $stmt = "SELECT * FROM checkin WHERE created_at >= '" . date("Y-m-d H:i:s", strtotime("now - 1 hour")) . "';";
        $result = $this->execute($stmt, array());
        $models = $this->hydrate_result($result);
        return $models;
    }
}