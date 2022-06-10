<?php
	require 'config/config.php';

	require 'classes/user.php';
	require 'classes/gameTerm.php';
	require 'classes/game.php';

	if (!isset($_POST["search"]) || empty($_POST["search"])) {
		redirect("index.php");
	}

	require 'templates/header.php';

	$query = "SELECT id FROM `games` WHERE title LIKE '%".mysqli_real_escape_string($db, $_POST["search"])."%'";
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
			<?php
				foreach ($games as $game) {
					echo '<div class="card text-center">
				<div class="card-header">
					'.$game->title.'
				</div>
				<div class="card-body">
					<p class="card-text">'.$game->description.'</p>
					<a href="viewGame.php?game='.$game->id.'" class="btn btn-primary">See this set!</a>
					<br><br>
					<p class="text-muted" style="display: inline-block; text-align: center; margin-bottom: 0;">Created By <a href="profile.php?id='.$game->user->id.'">'.$game->user->username.'</a></p>
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