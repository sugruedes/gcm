<?php
# Registers client ID to table...

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

var_dump($input);

$id = $input->registration_ids;
$msg = $input->data[0]->message;

echo "ID id $id\n";
echo "Message is ".$msg."\n";

if (!in_array($id, loadUsers())) adduser($id);

?>
