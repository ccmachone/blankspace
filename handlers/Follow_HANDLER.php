<?php
class Follow_HANDLER extends \Handler {
    public function handleGet($params)
    {
        if (isset($params['id'])) {
            return parent::handleGet($params);
        } else {
            $ref = new \ReflectionClass($this);
            $class = strtolower(str_replace("_HANDLER", "", $ref->getName()));
            $da_name = $class . "_DA";
            $da = new $da_name();

            if (isset($params['user_id'])) {
                $models = $da->getByUserId($params['user_id']);
            } else {
                $models = $da->getAll();
            }
            $array_models = array();
            foreach ($models as $model) {
                $array_models[] = $model->toArray();
            }
            return $array_models;
        }
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
            $existing_obj = $da->getByUserIdFollowingId($params['user_id'], $params['following_id']);
            if ($existing_obj->getPersisted()) {
                $errors[] = "Follow already exists!";
            } else {
                $obj->save();
                return array($obj->toArray());
            }
        }
        return array("errors" => $errors);
    }
}