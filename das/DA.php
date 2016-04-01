<?php
abstract class DA {
    private static $root_dir = "/BlankSpace";

    public function getById($id)
    {
        $stmt = "";
        $ref = new \ReflectionClass($this);
        $table = strtolower(str_replace("_DA", "", $ref->getName()));
        $stmt .= "SELECT * FROM " . $table . " WHERE id = ?;";
        $values = array();
        $values[] = array("id" => $id);

        var_dump($stmt);
        var_dump($values);
        $result = $this->execute($stmt, $values);
        $model = $this->hydrate_result($result);
        var_dump($model);
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
        return $q;
    }

    private function hydrate_result($result)
    {
        $ref = new \ReflectionClass($this);
        $class = ucfirst(strtolower(str_replace("_DA", "", $ref->getName())));
        $models = array();
        foreach ($result->fetchAll() as $key => $values) {
            $obj = new $class();
            $obj->hydrateFromArray($values);
            $models[] = $obj;
        }
        if (count($models) == 1) {
            return $models[0];
        } else {
            return $models;
        }
    }

}