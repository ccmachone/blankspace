<?php
class User extends \Model {
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone1;
    private $phone1_carrier_id;
    protected $_required_attributes = array("first_name", "last_name", "email", "phone1");

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
     * @return mixed
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * @param mixed $phone1
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }

    /**
     * @return mixed
     */
    public function getPhone1_carrier_id()
    {
        return $this->phone1_carrier_id;
    }

    /**
     * @param mixed $phone1_carrier_id
     */
    public function setPhone1_carrier_id($phone1_carrier_id)
    {
        $this->phone1_carrier_id = $phone1_carrier_id;
    }




    public function getFollowers()
    {
        $follow_da = new \Follow_DA();
        $user_da = new \User_DA();
        $follows = $follow_da->getByFollowingId($this->getId());
        $followers = array();
        foreach ($follows as $follow) {
            $followers[] = $user_da->getById($follow->getUser_id());
        }
        return $followers;
    }


}