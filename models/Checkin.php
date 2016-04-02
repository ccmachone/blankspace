<?php
class Checkin extends \Model {
    private $id;
    private $user_id;
    private $latitude;
    private $longitude;
    private $map_url;
    private $address;
    protected $_required_attributes = array("user_id", "latitude", "longitude");

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
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getMap_url()
    {
        return $this->map_url;
    }

    /**
     * @param mixed $map_url
     */
    public function setMap_url($map_url)
    {
        $this->map_url = $map_url;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    protected function model_persist_pre_hook()
    {
        $geocoder = new \Geocoder();
        if ($this->getMap_url() == null) {
            $map_url = $geocoder->getMapUrlAtLatitudeLongitude($this->getLatitude(), $this->getLongitude());
            $this->setMap_url($map_url);
        }
        if ($this->getAddress() == null) {
            $address = $geocoder->getAddressAtLatitudeLongitude($this->getLatitude(), $this->getLongitude());
            $this->setAddress($address);
        }
    }

    protected function model_persist_post_hook()
    {
        //TODO: send an email
    }

    public function getMailBody(\User $checkin_user)
    {
        $message = $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . " just checked in to " . $this->getAddress() . "!\n";
        $message .= "Don't know where that is?  Find out here: " . $this->getMap_url() . "\n";
        $message .= "Thanks for using BlankSpace!";
        return $message;
    }



}