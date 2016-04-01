<?php
abstract class Handler {
    public function handleGet($params)
    {
        $ref = new \ReflectionClass($this);
        $class = strtolower(str_replace("_HANDLER", "", $ref->getName()));
        $da_name = $class . "_DA";
        $da = new $da_name();

        if (isset($params['id'])) {
            $models = array($da->getById($params['id']));
        } else {
            $models = $da->getAll();
        }
        $array_models = array();
        foreach ($models as $model) {
            $array_models[] = $model->toArray();
        }
        return $array_models;
    }

    public function handlePost($params)
    {
        $ref = new \ReflectionClass($this);
        $class = strtolower(str_replace("_HANDLER", "", $ref->getName()));
        $da_name = $class . "_DA";
        $da = new $da_name();

        $obj = new $class();

        $required_attributes = $obj->getRequiredAttributes();
        $errors = array();
        foreach ($required_attributes as $required_attribute) {
            if (isset($params[$required_attribute])) {
                $setter = "set" . $required_attribute;
                $obj->$setter($_POST[$required_attribute]);
            } else {
                $errors[] = $required_attribute . " is required!";
            }
        }
        if (count($errors) == 0) {
            $obj->save();
            return array($obj->toArray());
        } else {
            return array("errors" => $errors);
        }

    }
}