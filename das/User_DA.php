<?php
class User_DA extends \DA {
    public function getByEmail($email)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE email = ?;";
        $values = array();
        $values[] = array("email" => $email);

        $result = $this->execute($stmt, $values);
        $model = $this->hydrate_result($result);
        return $model;
    }
}