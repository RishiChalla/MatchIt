<?php
	$db = mysqli_connect("localhost", $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

	if (mysqli_connect_errno()) {
		foreach ($ERROR_EMAILS as $email) {
			mail($email, "Error in your website", "There was a error in your website today at ".date('Y/m/d H:i:s').".\nHere is the error report: ".mysqli_connect_error());
		}
		die("Error");
	}
?>