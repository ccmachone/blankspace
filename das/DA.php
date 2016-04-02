<?php
abstract class DA {
    public function getById($id)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE id = ?;";
        $values = array();
        $values[] = array("id" => $id);

        $result = $this->execute($stmt, $values);
        $model = $this->hydrate_result($result);
        return $model;
    }

    public function getAll()
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . ";";
        $result = $this->execute($stmt, array());
        $models = $this->hydrate_result($result);
        return $models;
    }

    protected function execute($stmt, $values)
    {
        $ini = getProjectIni();
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
        return $q;
    }

    protected function hydrate_result($result)
    {
        $ref = new \ReflectionClass($this);
        $class = ucfirst(strtolower(str_replace("_DA", "", $ref->getName())));
        $models = array();
        foreach ($result->fetchAll() as $key => $values) {
            $obj = new $class();
            $obj->hydrateFromArray($values);
            $models[] = $obj;
        }
        if (count($models) == 0) {
            return new $class();
        } elseif (count($models) == 1) {
            return $models[0];
        } else {
            return $models;
        }
    }

}