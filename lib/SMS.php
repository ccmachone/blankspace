<?php
class SMS {
    public function send(\User $recipient, $message)
    {
        $ini = getProjectIni();
        if ($recipient->getPhone1_carrier_id() == null && !in_array($recipient->getPhone1(), $ini['twilio']['verified_phone_numbers'])) {
            return;
        } else {
            if ($recipient->getPhone1_carrier_id() != null) {
                $sms = new SMS_Email();
            } else {
                $sms = new SMS_Twilio();
            }
            $sms->send($recipient, $message);
        }
    }
}