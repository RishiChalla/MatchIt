<?php
	session_start();
	$logged = false;
	if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
		$logged = true;
		$query = "SELECT * FROM `users` WHERE id='".mysqli_real_escape_string($db, $_SESSION["id"])."'";
		if ($result = mysqli_query($db, $query)) {
			$rawUser = mysqli_fetch_assoc($result);
			$user = new User();
			$user->id = $rawUser["id"];
			$user->password = $rawUser["password"];
			$user->username = $rawUser["username"];
			$user->email = $rawUser["email"];
			$user->dateCreated = $rawUser["created"];
		}
		else {
			errorMails($ERROR_EMAILS, mysqli_error($db));
			die();
		}
	}
	if (isset($mustBeLogged) && $mustBeLogged == true) {
		if (!$logged) {
			redirect($WEBSITE_ROOT."login.php");
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>MatchIt</title>
		<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<?php echo '<link rel="stylesheet" type="text/css" href="'.$WEBSITE_ROOT.'css/stylesheet.css">' ?>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
		<?php echo '<script type="text/javascript" src="'.$WEBSITE_ROOT.'js/gameTerm.js"></script>'; ?>
		<?php echo '<script type="text/javascript" src="'.$WEBSITE_ROOT.'js/game.js"></script>'; ?>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<?php echo '<a class="navbar-brand" href="'.$WEBSITE_ROOT.'">MatchIt</a>'; ?>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<?php if ($logged == true): ?>
					<li class="nav-item">
						<?php echo '<a class="nav-link" href="'.$WEBSITE_ROOT.'newGame.php">Create New Game</a>'; ?>
					</li>
					<li class="nav-item dropdown">
						<?php echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> '.$user->username.' </a>'; ?>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<?php echo '<a class="dropdown-item" href="'.$WEBSITE_ROOT.'profile.php"><i class="fas fa-user-circle"></i> View Profile</a>'; ?>
							<?php echo '<a class="dropdown-item" href="'.$WEBSITE_ROOT.'logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>'; ?>
						</div>
					</li>
					<?php else: ?>
					<li class="nav-item">
						<?php echo '<a class="nav-link" href="'.$WEBSITE_ROOT.'login.php">Login</a>'; ?>
					</li>
					<li class="nav-item">
						<?php echo '<a class="nav-link" href="'.$WEBSITE_ROOT.'signup.php">Signup</a>'; ?>
					</li>
					<?php endif; ?>
				</ul>
				<?php echo '<form class="form-inline my-2 my-lg-0" method="post" action="'.$WEBSITE_ROOT.'search.php">'; ?>
					<input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
				</form>
			</div>
		</nav>
		<div class="container">
