<?php
class SMS_Email {
    public function send(\User $recipient, $message) {
        $carrier_da = new \Carrier_DA();
        $carrier = $carrier_da->getById($recipient->getPhone1_carrier_id());
        if ($carrier->getPersisted()) {
            $ini = getProjectIni();
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
            $mail->setBody($message);
            $mail->setTo($recipient->getPhone1() . "@" . $carrier->getSms_domain());
            $transport->send($mail);
        } else {
            throw new \Exception("Can't send Text Message to user without a carrier!");
        }
    }
}