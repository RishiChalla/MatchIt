<?php
	require 'config/vars.php';
	session_start();
	session_destroy();
	header("Location: ".$WEBSITE_ROOT);
	exit();
?>