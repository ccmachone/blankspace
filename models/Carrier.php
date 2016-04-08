<?php
class Carrier extends \Model {
    private $id;
    private $name;
    private $sms_domain;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSms_domain()
    {
        return $this->sms_domain;
    }

    /**
     * @param mixed $sms_domain
     */
    public function setSms_domain($sms_domain)
    {
        $this->sms_domain = $sms_domain;
    }

}