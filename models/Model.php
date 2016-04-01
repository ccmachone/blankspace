<?php
abstract class Model {

    private static $root_dir = "/BlankSpace";
    private $persisted = false;

    public function getPersisted()
    {
        return $this->persisted;
    }

    private function setPersisted($persisted)
    {
        $this->persisted = $persisted;
    }
    public function save()
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower($ref->getName());
        $properties = $ref->getProperties();
        $mode = "";

        if ($this->getPersisted()) {
            $mode = "update";
            $stmt = "UPDATE $table SET ";
            $values = array();
            $i = 0;
            foreach ($properties as $property) {
                $i++;
                $property_name = $property->name;
                if ($property_name != "id" && substr($property_name, 0, 1) != "_") {
                    $comma = $i < count($properties) ? ", " : " ";
                    $stmt .= $property_name . " = ?" . $comma;
                    $getter = "get" . ucfirst($property_name);
                    $values[] = array($property_name = $this->$getter());
                }
            }
            $stmt .= "WHERE id = ?;";
            $values[] = array('id' => $this->getId());
        } else {
            $mode = "create";
            $stmt = "INSERT INTO $table (";
            $stmt_values = "(";
            $values = array();
            $i = 0;
            foreach ($properties as $property) {
                $i++;
                $property_name = $property->name;
                if ($property_name != "id" && substr($property_name, 0, 1) != "_") {
                    $comma = $i < count($properties) ? ", " : ") ";
                    $stmt .= $property_name . $comma;
                    $stmt_values .= "?" . $comma;
                    $getter = "get" . ucfirst($property_name);
                    $values[] = array($property_name = $this->$getter());
                }
            }
            $stmt .= "VALUES " . $stmt_values . ";";
        }


        $result = $this->execute($stmt, $values);
        if (is_numeric($result) && $mode == "create") {
            $this->setId($result);
            $this->setPersisted(true);
        }
    }

    public function delete()
    {
        $values = array();
        $ref = new \ReflectionClass($this);
        $table = strtolower($ref->getName());
        $stmt = "DELETE FROM $table WHERE id = ?;";
        $values[] = array("id" => $this->getId());

        $this->execute($stmt, $values);
    }

    private function execute($stmt, $values)
    {
        $ini = parse_ini_file(self::$root_dir . "/conf.ini", true);
        try {
            $dbh = new \PDO('mysql:host=' . $ini['database']['server'] . ";dbname=" . $ini['database']['name'], $ini['database']['username'], $ini['database']['password']);
            $q = $dbh->prepare($stmt);
            foreach ($values as $key => $value) {
                foreach ($value as $property_name => $property_value) {
                    $q->bindParam($key + 1, $values[$key][$property_name]);
                }
            }
            $result = $q->execute();
            var_dump($result);
        } catch (\PDOException $e) {
            //TODO: log it...
            throw $e;
        }
        if ($result == true) {
            return $dbh->lastInsertId();
        } else {
            return $result;
        }
    }

    public function hydrateFromArray($arr)
    {
        foreach ($arr as $property => $value) {
            if (is_numeric($property)) {
                continue;
            } else {
                $setter = "set" . ucfirst($property);
                $this->$setter($value);
            }
        }
        $this->setPersisted(true);
    }
}