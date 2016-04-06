<?php
include_once("DA.php");

class Follow_DA extends \DA {
    public function getByUserId($user_id)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE user_id = ?;";
        $values = array();
        $values[] = array("user_id" => $user_id);

        $result = $this->execute($stmt, $values);
        $models = $this->hydrate_result($result);
        return $models;
    }

    public function getByUserIdFollowingId($user_id, $following_id)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE user_id = ? AND following_id = ?;";
        $values = array();
        $values[] = array("user_id" => $user_id);
        $values[] = array("following_id" => $following_id);

        $result = $this->execute($stmt, $values);
        $models = $this->hydrate_result($result);
        return $models;
    }

    public function getByFollowingId($following_id)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE following_id = ?;";
        $values = array();
        $values[] = array("following_id" => $following_id);

        $result = $this->execute($stmt, $values);
        $models = $this->hydrate_result($result);
        if (!is_array($models)) {
            $models = array($models);
        }
        return $models;
    }
}