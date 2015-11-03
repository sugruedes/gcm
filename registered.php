<?php
# Responds to incoming event from app when it revieves regid from GCM
# Registers client ID to table and sends back notification...

include 'ChatProtoPushMessage.php';

function loadUsers() {
	# for now, a flatfile, but requires a db adapter
	$tmp = file("users.db", FILE_SKIP_EMPTY_LINES);
	$users = array_map('trim', $tmp);
	return $users;
}

function addUser($userData) {
	$fh = fopen("users.db", "a");
	fwrite($fh, $userData."\n");
	fclose($fh);
}

#php://input returns the raw data from the request
$input = json_decode(file_get_contents("php://input"));

$id = $input->registration_ids;
$msg = $input->data[0]->message;

echo "ID id $id\n";
echo "Message is ".$msg."\n";

#hardcoded for testing only:
$multicast_id = "5574652367352264803";
$message_id = "0:1446505844247516%934ed4baf9fd7ecd";


if (!in_array($id, loadUsers())) {
	#Build a record for the file
	$record = $id."/".$multicast_id."/".$message_id;
	adduser($record);

	#send back an ack via gcm
	$toUrl = "https://android.googleapis.com/gcm/send";
	$apiKey = "AIzaSyAFTdicdJZKMBVdsC-ygVu2b4JehB-cIQQ";

	$devices = array($id);
	$pm = new ChatProtoPushMessage($api_key, $url);
	$pm->setDevices($devices);

	$response = $pm->send($msg);
	return $response;

} else {
	#Its already registered, so silently discard.
	return 0;

}

?>
