<?php
class User extends \Model {
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $_required_attributes = array("first_name", "last_name", "email");

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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



    protected function model_persist_pre_hook()
    {
        echo "In the user pre hook";
    }

    protected function model_persist_post_hook()
    {
        echo "In the user post hook";
    }


}