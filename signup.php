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
						<label for="username">Username</label>
						<input type="text" name="username" class="form-control" id="username" placeholder="Type Username">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="rpassword">Repeat Password</label>
						<input type="password" name="rpassword" class="form-control" id="rpassword" placeholder="Type your Password again">
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
					$rpassword = $_POST["rpassword"];
					$username = $_POST["username"];
					$error = false;
					$errorMessage = "";

					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$errorMessage = "Invalid email format"; 
						$error = true;
					}

					if (strlen($password) > 200) {
						$errorMessage = "Your password is too long";
						$error = true;
					}

					if (strlen($username) > 200) {
						$errorMessage = "Your username is too long";
						$error = true;
					}

					if (strlen($username) < 3) {
						$errorMessage = "Your username is too short";
						$error = true;
					}

					if (strlen($password) < 5) {
						$errorMessage = "Your password is too short";
						$error = true;
					}

					if (empty($email) || !isset($email) || empty($password) || !isset($password) || empty($rpassword) || !isset($rpassword) || empty($username) || !isset($username)) {
						$errorMessage = "Please fill in all fields";
						$error = true;
					}

					$query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($db, $email)."'";
					if ($result = mysqli_query($db, $query)) {
						if (mysqli_num_rows($result) != 0) {
							$error = true;
							$errorMessage = "That email address is already taken";
						}
					}
					else {
						echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>Internal Error, try again later.</div>';
						errorMessage(mysqli_error($db));
					}

					if ($error) {
						echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>'.$errorMessage.'</div>';
					}
					else {
						$password = password_hash($password, PASSWORD_DEFAULT);
						$query = "INSERT INTO `users` (username, password, email) VALUES ('".mysqli_real_escape_string($db, $username)."', '".mysqli_real_escape_string($db, $password)."', '".mysqli_real_escape_string($db, $email)."')";
						if (mysqli_query($db, $query)) {
							echo '<div class="alert alert-success" role="alert"><strong>Success: </strong>We have successfully created your account.</div>';
						}
						else {
							echo '<div class="alert alert-danger" role="alert"><strong>Error: </strong>Internal Error, try again later.</div>';
							errorMessage(mysqli_error($db));
						}
					}
				}
			?>
<?php
	require 'templates/footer.php';
?>