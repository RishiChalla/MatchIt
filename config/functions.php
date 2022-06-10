<?php
	function redirect($url, $js) {
		if (isset($js) && $js == true) {
			echo '<script>window.location.replace("'.$url.'");</script>';
		}
		else {
			header("Location: ".$url);
			exit();
		}
	}

	function errorMails($ERROR_EMAILS, $message) {
		foreach ($ERROR_EMAILS as $email) {
			mail($email, "Error in your website", "There was a error in your website today at ".date('Y/m/d H:i:s').".\nHere is the error report: ".$message);
		}
	}
?>