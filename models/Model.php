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
        $this->callMethod("model_persist_pre_hook");

        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower($ref->getName());
        $properties = $ref->getProperties();
        $property_names = array();
        foreach ($properties as $property) {
            if (substr($property->name, 0, 1) != "_" && $property->name != "id") {
                $property_names[] = $property->name;
            }
        }

        if ($this->getPersisted()) {
            $mode = "update";
            $stmt = "UPDATE $table SET ";
            $values = array();
            $i = 0;
            foreach ($property_names as $property_name) {
                $i++;
                $comma = $i < count($property_names) ? ", " : " ";
                $stmt .= $property_name . " = ?" . $comma;
                $getter = "get" . ucfirst($property_name);
                $values[] = array($property_name = $this->$getter());
            }
            $stmt .= "WHERE id = ?;";
            $values[] = array('id' => $this->getId());
        } else {
            $mode = "create";
            $stmt = "INSERT INTO $table (";
            $stmt_values = "(";
            $values = array();
            $i = 0;
            foreach ($property_names as $property_name) {
                $i++;
                $comma = $i < count($property_names) ? ", " : ") ";
                $stmt .= $property_name . $comma;
                $stmt_values .= "?" . $comma;
                $getter = "get" . ucfirst($property_name);
                $values[] = array($property_name = $this->$getter());
            }
            $stmt .= "VALUES " . $stmt_values . ";";
        }

        $result = $this->execute($stmt, $values);
        if (is_numeric($result) && $mode == "create") {
            $this->setId($result);
            $this->setPersisted(true);
        }

        $this->callMethod("model_persist_post_hook");
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

    private function callMethod($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    public function toArray()
    {
        $ref = new \ReflectionClass($this);
        $properties = $ref->getProperties();
        $array = array();
        foreach ($properties as $property) {
            $property_name = $property->name;
            if (substr($property_name, 0, 1) != "_") {
                $getter = "get" . ucfirst($property_name);
                $array[$property_name] = $this->$getter();
            }
        }
        return $array;
    }

    /**
     * @return array
     */
    public function getRequiredAttributes()
    {
        return $this->_required_attributes;
    }

    /**
     * @param array $required_attributes
     */
    public function setRequiredAttributes($required_attributes)
    {
        $this->_required_attributes = $required_attributes;
    }
}