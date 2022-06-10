<?php
	require 'config/config.php';

	require 'classes/user.php';
	require 'classes/gameTerm.php';
	require 'classes/game.php';

	require 'templates/header.php';
?>
			<form method="post">
				<form>
					<div class="form-group">
						<label for="email">Email address</label>
						<input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
						<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password">
					</div>
					<br>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<button type="submit" name="submit" value="notEmpty" class="btn btn-primary btn-block">Submit</button>
						</div>
						<div class="col-md-4"></div>
					</div>
					<br>
				</form>
			</form>
			<?php
				if (isset($_POST["submit"]) && !empty($_POST["submit"])) {
					unset($_POST["submit"]);
					$email = $_POST["email"];
					$password = $_POST["password"];
					$error = false;
					$errorMessage = "";

					if (empty($email) || !isset($email) || empty($password) || !isset($password)) {
						$errorMessage = "Please fill in all fields";
						$error = true;
					}

					if ($error) {
						echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>'.$errorMessage.'</div>';
					}
					else {
						$query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($db, $email)."'";
						if ($result = mysqli_query($db, $query)) {
							$rawUser = mysqli_fetch_assoc($result);
							$user = new User();
							$user->id = $rawUser["id"];
							$user->password = $rawUser["password"];
							$user->username = $rawUser["username"];
							$user->email = $rawUser["email"];
							$user->dateCreated = $rawUser["created"];
							if (password_verify($password, $user->password)) {
								$_SESSION["id"] = $user->id;
								echo '<div class="alert alert-success" role="alert"><strong>Success: </strong>We have successfully logged you in.</div>';
								redirect("index.php", true);
							}
							else {
								echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>Incorrect password.</div>';
							}
						}
						else {
							echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>Internal Error, try again later.</div>';
							errorMails($ERROR_EMAILS, mysqli_error($db));
						}
					}
				}
			?>
<?php
	require 'templates/footer.php';
?>