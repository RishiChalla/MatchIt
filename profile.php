<?php
	require 'config/config.php';

	require 'classes/user.php';
	require 'classes/gameTerm.php';
	require 'classes/game.php';

	require 'templates/header.php';

	if ($logged == true && (!isset($_GET["id"]) || empty($_GET["id"]))) {
		$id = $user->id;
	}
	else if (isset($_GET["id"]) && !empty($_GET["id"])) {
		$id = $_GET["id"];
	}
	else if ($logged == false) {
		redirect("index.php", true);
		die();
	}

	$profileUser = new User();
	$profileUser->id = $id;
	$profileUser->loadFromDatabase($db);

	$query = "SELECT id FROM `games` WHERE userId='".mysqli_real_escape_string($db, $id)."'";
	$games = [];
	if ($result = mysqli_query($db, $query)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$game = new Game();
			$game->id = $row["id"];
			$game->loadFromDatabase($db);
			array_push($games, $game);
		}
	}
	else {
		errorMails($ERROR_EMAILS, mysqli_error($db));
		die();
	}
?>
			<h1 style="font-size: 60px"><i class="fas fa-user-circle"></i> <?php echo $profileUser->username; ?></h1>
			<p>Account Created on <?php echo date("M d Y", strtotime($profileUser->dateCreated)); ?></p>
			<br>
			<?php
				foreach ($games as $game) {
					echo '<div class="card text-center">
				<div class="card-header">
					'.$game->title.'
				</div>
				<div class="card-body">
					<p class="card-text">'.$game->description.'</p>
					<a href="viewGame.php?game='.$game->id.'" class="btn btn-primary">See this set!</a>
				</div>
				<div class="card-footer text-muted">
					'.count($game->terms).' Terms. Created '.date("M d Y", strtotime($game->dateCreated)).'.
				</div>
			</div>
			<br>';
				}
			?>
<?php
	require 'templates/footer.php';
?>