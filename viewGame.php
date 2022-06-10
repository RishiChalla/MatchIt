<?php
	require 'config/config.php';

	require 'classes/user.php';
	require 'classes/gameTerm.php';
	require 'classes/game.php';

	if (!isset($_GET["game"]) || empty($_GET["game"])) {
		redirect("index.php", false);
	}

	$game = new Game();
	$game->id = $_GET["game"];
	$game->loadFromDatabase($db);

	require 'templates/header.php';
?>
			<h1 align="center"><?php echo $game->title; ?></h1>
			<h3>Made By: <?php echo '<a href="profile.php?id='.$game->user->id.'">'.$game->user->username.'</a>'; ?></h3>
			<br>
			<p><?php echo htmlspecialchars($game->description); ?></p>
			<br>
			<div class="row">
				<div class="col-md-6">
					<div class="card text-center" style="width: 100%;">
						<div class="card-body">
							<h3 class="card-title">MatchIt Live</h3>
							<p class="card-text">Play a live matching game with this list!</p>
							<?php echo '<a href="'.$WEBSITE_ROOT.'live/index.php?game='.$game->id.'" class="btn btn-primary">Play MatchIt Live</a>'; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card text-center" style="width: 100%;">
						<div class="card-body">
							<h3 class="card-title">Test</h3>
							<p class="card-text">Create a test with this list.</p>
							<?php echo '<a href="'.$WEBSITE_ROOT.'test.php?game='.$game->id.'" class="btn btn-primary">Create a Test</a>'; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="game">
				<?php
					foreach ($game->terms as $term) {
						echo '<div class="gameCard row">
					<div class="col-md-6">'.$term->term.'</div>
					<div class="col-md-6">'.$term->answer.'</div>
				</div>';
					}
				?>
			</div>
			<br>
			<?php if ($logged == true && $user->id == $game->user->id): ?>
			<?php echo '<a href="editGame.php?game='.$game->id.'" class="btn btn-primary btn-block">Edit Game</a>'; ?>
			<br>
			<?php endif; ?>
<?php
	require 'templates/footer.php';
?>