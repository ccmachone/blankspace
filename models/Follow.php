<?php
class Follow extends \Model {
    private $id;
    private $user_id;
    private $following_id;
    protected $_required_attributes = array("user_id", "following_id");

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
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getFollowing_id()
    {
        return $this->following_id;
    }

    /**
     * @param mixed $following_id
     */
    public function setFollowing_id($following_id)
    {
        $this->following_id = $following_id;
    }



}