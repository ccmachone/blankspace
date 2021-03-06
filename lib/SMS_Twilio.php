<?php
class SMS_Twilio {

	private $api_id;
	private $api_token;
	private $phone_number;

	function __construct() {
		$ini = getProjectIni();
		$this->api_id = $ini['sms']['api_id'];
		$this->api_token = $ini['sms']['api_token'];
		$this->phone_number = $ini['sms']['phone_number'];
	}

	public function send(\User $recipient, $link) {
        $client = new Services_Twilio($this->api_id, $this->api_token);
		try {
			$message = $client->account->messages->create(array(
			    "From" => $this->phone_number,
			    "To" => $recipient->getPhone1(),
			    "Body" => "{$link}",
			));
//			echo "Sent {$message->sid}";
		} catch (Services_Twilio_RestException $e) {
			// todo: log this bad boy
		} catch (Services_Twilio_HttpStreamException $e) {
			// todo: log this bad boy
		}
	}
}



