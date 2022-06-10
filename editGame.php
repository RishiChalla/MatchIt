<?php
	require 'config/config.php';

	require 'classes/user.php';
	require 'classes/gameTerm.php';
	require 'classes/game.php';

	$mustBeLogged = true;

	if (!isset($_GET["game"]) || empty($_GET["game"])) {
		redirect("index.php", false);
	}

	$game = new Game();
	$game->id = $_GET["game"];
	$game->loadFromDatabase($db);

	require 'templates/header.php';
?>
			<form method="post">
				<h1 align="center">Edit Game</h1>
				<br>
				<?php echo '<input type="text" placeholder="Title" name="title" class="form-control" value="'.$game->title.'">'; ?>
				<br>
				<?php echo '<textarea name="description" class="form-control" style="height:200px;min-height:200px;max-height:200px;" placeholder="Description">'.$game->description.'</textarea>'; ?>
				<div class="game">
					<?php
						$count = 0;
						foreach ($game->terms as $term) {
							$count = $count + 1;
							echo '<div class="term">
						<div class="row">
							<div class="col-md-6">
								<br>
								<textarea name="term'.$count.'" class="form-control term" style="height:100px;min-height:100px;max-height:100px;" placeholder="Term">'.$term->term.'</textarea>
							</div>
							<div class="col-md-6">
								<br>
								<textarea name="definition'.$count.'" class="form-control definition" style="height:100px;min-height:100px;max-height:100px;" placeholder="Definition">'.$term->answer.'</textarea>
							</div>
						</div>
						<br>
						<button type="button" class="removeTerm btn btn-block btn-danger">Remove Term</button>
					</div>';
						}
					?>
				</div>
				<br>
				<button type="button" id="newTerm" class="btn btn-block btn-primary">Add New Term</button>
				<br>
				<button class="btn btn-block btn-success" value="Update Game" name="submit">Update Game</button>
				<br>
			</form>
			<?php
				if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
					unset($_POST["submit"]);
					if (empty($_POST["title"]) || !isset($_POST["title"]) || empty($_POST["description"]) || !isset($_POST["description"])) {
						echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>Please enter the title and description.</div>';
					}
					else {
						$game->title = $_POST["title"];
						$game->description = $_POST["description"];
						$count = 1;
						while (isset($_POST["term".$count]) && !empty($_POST["term".$count]) && isset($_POST["definition".$count]) && !empty($_POST["definition".$count])) {
							$term = $_POST["term".$count];
							$definition = $_POST["definition".$count];
							$gameTerm = new gameTerm();
							$gameTerm->id = -1;
							$gameTerm->term = $term;
							$gameTerm->answer = $definition;
							array_push($game->terms, $gameTerm);
							$count += 1;
						}
						$game->update($db);
						echo '<div class="alert alert-success" role="alert"><strong>Success: </strong>Successfully created the game.</div>';
						redirect("viewGame.php?game=".$game->id, true);
					}
				}
			?>
			<script type="text/javascript" src="js/editGame.js"></script>
<?php
	require 'templates/footer.php';
?>