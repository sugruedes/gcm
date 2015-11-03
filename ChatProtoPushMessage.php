<?php

/*  push a message to ChatProto server
*
* This is a trivial implementation to prototype a go server
* based on work by Matt Grundy
*
*	Adapted from the code available at:
*	http://stackoverflow.com/questions/11242743/gcm-with-php-google-cloud-messaging
*
*
*/

class ChatProtoPushMessage {

	var $url = 'http://test.des/gcm/registered.php';
	var $serverApiKey = "";
	var $devices = array();
	
	/*
		Constructor
		@param $apiKeyIn the server API key
	*/
	function ChatProtoPushMessage($apiKeyIn){
    		print "Constructor with $apiKeyIn\n";
		$this->serverApiKey = $apiKeyIn;
	}

	/*
		Set the devices to send to
		@param $deviceIds array of device tokens to send to
	*/
	function setDevices($deviceIds){
	
		if(is_array($deviceIds)){
			$this->devices = $deviceIds;
		} else {
			$this->devices = array($deviceIds);
		}
	
	}

	/*
		Send the message to the device
		@param $message The message to send
		@param $data Array of data to accompany the message
	*/
	function send($message, $data = false){

		echo "In send(message)\n";
		
		if(!is_array($this->devices) || count($this->devices) == 0){
			$this->error("No devices set");
		}
		
		if(strlen($this->serverApiKey) < 8){
			$this->error("Server API Key not set");
		}
		
		$fields = array(
			'registration_ids'  => $this->devices,
			'data'              => array( "message" => $message ),
		);
		
		if(is_array($data)){
			foreach ($data as $key => $value) {
				$fields['data'][$key] = $value;
			}
		}

		$headers = array( 
			'Authorization: key=' . $this->serverApiKey,
			'Content-Type: application/json'
		);


		// Open connection
		$ch = curl_init();
		
		// Set the url, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $this->url );
		
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

		// Avoids problem with https certificate
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		
		// Execute post
		if( ! $result = curl_exec($ch)) 
		    { 
		        $this->error("Error with curl..."); 
		    } 
		
		// Close connection
		curl_close($ch);
		
		return $result;
	}
	
	function error($msg){
		echo "ChatProto send notification failed with error:";
		echo "\t" . $msg . "\n";
		exit(1);
	}
}
?>
