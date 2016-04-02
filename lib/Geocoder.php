<?php
class Geocoder {
    private $adapter;

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    public function __construct($adapter = null)
    {
        if ($this->getAdapter() == null) {
            $this->setAdapter(new \Geocoder_Google_Maps());
        } else {
            $this->setAdapter($adapter);
        }
    }


    public function getMapUrlAtLatitudeLongitude($latitude, $longitude)
    {
        return $this->adapter->getMapUrlAtLatitudeLongitude($latitude, $longitude);
    }

    public function getAddressAtLatitudeLongitude($latitude, $longitude)
    {
        return $this->adapter->getAddressAtLatitudeLongitude($latitude, $longitude);
    }
}