<?php
class Checkin extends \Model {
    private $id;
    private $user_id;
    private $latitude;
    private $longitude;
    private $map_url;
    private $address;
    private $created_at;
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

    /**
     * @return mixed
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }



    protected function model_persist_pre_hook()
    {
        $this->setCreated_at(date("c"));

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
        $ini = getProjectIni();

        $user_da = new \User_DA();
        $checkin_da = new \Checkin_DA();
        $follow_da = new \Follow_DA();

        $checkin_user = $user_da->getById($this->getUser_id());
        $checkins_in_last_hour = count($checkin_da->getAllInLastHour());
        $follows = $follow_da->getByFollowingId($checkin_user->getId());
        $followers = array();
        foreach ($follows as $follow) {
            $followers[] = $user_da->getById($follow->getUser_id());
        }

        if ($ini['email']['enabled'] && $checkins_in_last_hour < $ini['email']['per_hour_limit']) {
            $transport = new \Zend\Mail\Transport\Smtp(new \Zend\Mail\Transport\SmtpOptions(array(
                'name' => 'smtp.gmail.com',
                'host' => 'smtp.gmail.com',
                'port' => 25,
                'connection_class' => 'plain',
                'connection_config' => array(
                    'username' => $ini['email']['username'],
                    'password' => $ini['email']['password'],
                    'ssl' => 'tls',
                ),
            )));
            foreach ($followers as $follower) {
                $mail = new \Zend\Mail\Message();
                $mail->setFrom($ini['email']['username']);
                $mail->setSubject($this->getMailSubject($checkin_user));
                $mail->setBody($this->getMailBody($checkin_user));
                $mail->setTo($follower->getEmail());
                $transport->send($mail);
            }

        }

        if ($ini['sms']['enabled'] && $checkins_in_last_hour < $ini['sms']['per_hour_limit']) {
            $sms = new \SMS();
            foreach ($followers as $follower) {
                $sms->send($follower, $this->getSmsBody($checkin_user, $follower));
            }
        }
    }

    public function getMailSubject(\User $checkin_user)
    {
        return "New BlankSpace checkin for " . $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . "!";
    }

    public function getMailBody(\User $checkin_user)
    {
        $message = $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . " just checked in to " . $this->getAddress() . "!\n";
        $message .= "Don't know where that is?  Take a look at this map and find out: " . $this->getMap_url() . "\n";
        $message .= "Do you Care? Click here to show it: " . $this->getSentimentLinkForUserSentiment($checkin_user,1) . "\n";
        $message .= "Don't Care? Click here to express your apathy: " . $this->getSentimentLinkForUserSentiment($checkin_user) . "\n";
        $message .= "Thanks for using BlankSpace!";
        return $message;
    }

    public function getSentimentLinkForUserSentiment(\User $user, $sentiment = 0)
    {
        $ini = getProjectIni();
        $front_end_url = $ini['front_end']['url'];
        return $front_end_url . "sentiment?user_id=" . $user->getId() . "&checkin_id=" . $this->getId() . "&does_care=" . $sentiment;
    }

    public function getSmsBody(\User $checkin_user, \User $follower)
    {
        $message = $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . " just checked in at " . $this->getAddress() . "!\n";
        $message .= "Map: " . $this->getMap_url() . "\n";
        $message .= "I Care! " . $this->getSentimentLinkForUserSentiment($follower,1) . "\n";
        $message .= "I Don't Care! " . $this->getSentimentLinkForUserSentiment($follower) . "\n";
        return $message;
    }
}