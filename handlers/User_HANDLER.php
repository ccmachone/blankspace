<?php
class User_HANDLER extends \Handler {
    public function handleGet($params)
    {
        if (isset($params['id'])) {
            return parent::handleGet($params);
        } else {
            $ref = new \ReflectionClass($this);
            $class = strtolower(str_replace("_HANDLER", "", $ref->getName()));
            $da_name = $class . "_DA";
            $da = new $da_name();

            if (isset($params['email'])) {
                $models = array($da->getByEmail($params['email']));
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
        if (isset($_POST['phone1_carrier_id'])) {
            $obj->setPhone1_carrier_id($_POST['phone1_carrier_id']);
        }
        $existing_obj = $da->getByEmail($params['email']);
        if ($existing_obj->getPersisted()) {
            $errors[] = "Account with specified email already exists!";
        }

        $existing_obj = $da->getByPhone1($params['phone1']);
        if ($existing_obj->getPersisted()) {
            $errors[] = "Account with specified phone1 already exists!";
        }

        if (!valid_phone($params['phone1'])) {
            $errors[] = "Phone1 is invalid!";
        }

        if (count($errors) == 0) {
            $obj->save();
            return array($obj->toArray());
        } else {
            return array("errors" => $errors);
        }
    }
}