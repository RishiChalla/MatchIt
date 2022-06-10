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
			<form id="testOptions">
				<h1>Create a new Test</h1>
				<label>Amount of Questions</label>
				<?php echo '<input type="number" class="form-control" placeholder="Amount of Questions" id="questionAmount" min="1" max="'.count($game->terms).'">'; ?>
				<br>
				<label>Question Types:</label>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="mcq" checked>
					<label class="form-check-label" for="mcq">Multiple Choice Questions</label>
				</div>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="wq" checked>
					<label class="form-check-label" for="wq">Written Questions</label>
				</div>
				<br>
				<label>Answer with:</label>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="term" checked>
					<label class="form-check-label" for="term">Term</label>
				</div>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="definition" checked>
					<label class="form-check-label" for="definition">Definition</label>
				</div>
				<br>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<button type="button" id="createTest" class="btn btn-primary btn-block">Create Test</button>
					</div>
					<div class="col-md-4"></div>
				</div>
			</form>
			<form id="test">
				<?php echo '<h1>Test - '.$game->title.'</h1>'; ?>
				<ol id="testQuestions"></ol>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<button type="button" id="gradeTest" class="btn btn-block btn-primary">Grade Test</button>
					</div>
					<div class="col-md-4"></div>
				</div>
				<br>
			</form>
			<?php
				function propperString($string) {
					$s = str_replace("\"", "\\\"", $string);
					$s = trim(preg_replace('/\s\s+/', ' ', $s));
					return $s;
				}

				echo '<script type="text/javascript">var game = new Game("'.propperString($game->title).'", "'.propperString($game->description).'", "'.$game->dateCreated.'", []);';
				foreach ($game->terms as $term) {
					echo 'game.terms.push(new GameTerm("'.propperString($term->term).'", "'.propperString($term->answer).'"));';
				}
				echo '</script>';
			?>
			<script type="text/javascript" src="js/test.js"></script>
<?php
	require 'templates/footer.php';
?>