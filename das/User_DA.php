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

    public function getByPhone1($phone1)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE phone1 = ?;";
        $values = array();
        $values[] = array("phone1" => $phone1);

        $result = $this->execute($stmt, $values);
        $model = $this->hydrate_result($result);
        return $model;
    }
}