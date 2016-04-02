<?php
interface Geocoder_Adapter_Interface {
    public function getMapUrlAtLatitudeLongitude($latitude, $longitude);
    public function getAddressAtLatitudeLongitude($latitude, $longitude);
}