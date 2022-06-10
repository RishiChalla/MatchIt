<?php
	class User {
		public $id;
		public $username;
		public $password;
		public $email;
		public $dateCreated;

		public function loadFromDatabase($db) {
			$query = "SELECT * FROM `users` WHERE id='".mysqli_real_escape_string($db, $this->id)."'";
			if ($result = mysqli_query($db, $query)) {
				$row = mysqli_fetch_assoc($result);
				$this->username = $row["username"];
				$this->password = $row["password"];
				$this->email = $row["email"];
				$this->dateCreated = $row["created"];
			}
			else {
				errorMails($ERROR_EMAILS, mysqli_error($db));
				die();
			}
		}
	}
?>