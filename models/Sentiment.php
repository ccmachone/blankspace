<?php
class Sentiment extends \Model {
    private $id;
    private $user_id;
    private $checkin_id;
    private $does_care;
    private $created_at;
    protected $_required_attributes = array("user_id", "checkin_id", "does_care");

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
    public function getCheckin_id()
    {
        return $this->checkin_id;
    }

    /**
     * @param mixed $checkin_id
     */
    public function setCheckin_id($checkin_id)
    {
        $this->checkin_id = $checkin_id;
    }

    /**
     * @return mixed
     */
    public function getDoes_care()
    {
        return $this->does_care;
    }

    /**
     * @param mixed $does_care
     */
    public function setDoes_care($does_care)
    {
        $this->does_care = $does_care;
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
    }

    protected function model_persist_post_hook()
    {
        $ini = getProjectIni();

        $user_da = new \User_DA();
        $checkin_da = new \Checkin_DA();
        $sentiment_da = new \Sentiment_DA();

        $checkin = $checkin_da->getById($this->getCheckin_id());
        $checkin_user = $user_da->getById($checkin->getUser_id());
        $sentiment_user = $user_da->getById($this->getUser_id());

        $sentiments_in_last_hour = count($sentiment_da->getAllInLastHour());

        if ($ini['email']['enabled'] && $sentiments_in_last_hour < $ini['email']['per_hour_limit']) {
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

            $mail = new \Zend\Mail\Message();
            $mail->setFrom($ini['email']['username']);
            $mail->setSubject($this->getMailSubject($checkin_user));
            $mail->setBody($this->getMailBody($checkin_user, $sentiment_user, $checkin));

            foreach ($checkin_user->getFollowers() as $follower) {
                $mail->addBcc($follower->getEmail());
            }

            $transport->send($mail);
        }

        if ($ini['sms']['enabled'] && $sentiments_in_last_hour < $ini['sms']['per_hour_limit']) {
            //TODO: send SMS

        }
    }

    public function getMailSubject(\User $checkin_user)
    {
        return "New BlankSpace Sentiment for " . $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . "'s Checkin!";
    }

    public function getMailBody(\User $checkin_user, \User $sentiment_user, \Checkin $checkin)
    {
        $message = $sentiment_user->getFirst_name() . " " . $sentiment_user->getLast_name() . " " . ($this->getDoes_care() == "1" ? "cares" : "doesn't care") . " that ";
        $message .= $checkin_user->getFirst_name() . " " . $checkin_user->getLast_name() . " just checked in to " . $checkin->getAddress() . "!\n";
        $message .= "Thanks for using BlankSpace!";
        return $message;
    }



}