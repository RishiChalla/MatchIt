<?php
	require '../config/config.php';

	require '../classes/liveUser.php';
	require '../classes/gameTerm.php';
	require '../classes/game.php';

	if (!isset($_GET["game"]) && empty($_GET["game"])) {
		redirect("play.php");
	}

	$mustBeLogged = true;

	require '../templates/header.php';

	
?>

<?php
	require '../templates/footer.php';
?>