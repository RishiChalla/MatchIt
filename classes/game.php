<?php
	class Game {
		public $id;
		public $user;
		public $title;
		public $description;
		public $dateCreated;
		public $terms = array();

		public function create($db) {
			$query = "INSERT INTO `games` (title, userId, description) VALUES ('".mysqli_real_escape_string($db, $this->title)."', '".mysqli_real_escape_string($db, $this->user->id)."', '".mysqli_real_escape_string($db, $this->description)."')";
			if (mysqli_query($db, $query)) {
				$this->id = mysqli_insert_id($db);
				foreach ($this->terms as $key => $term) {
					$query = "INSERT INTO `gameTerm` (gameId, term, answer) VALUES ('".mysqli_real_escape_string($db, $this->id)."', '".mysqli_real_escape_string($db, $term->term)."', '".mysqli_real_escape_string($db, $term->answer)."')";
					if (!mysqli_query($db, $query)) {
						errorMails($ERROR_EMAILS, mysqli_error($db));
						die();
					}
				}
			}
			else {
				errorMails($ERROR_EMAILS, mysqli_error($db));
				die();
			}
		}

		public function update($db) {
			$query = "DELETE FROM `gameTerm` WHERE gameId='".mysqli_real_escape_string($db, $this->id)."'";
			if (!mysqli_query($db, $query)) {
				errorMails($ERROR_EMAILS, mysqli_error($db));
				die();
			}
			$query = "UPDATE `games` SET title='".mysqli_real_escape_string($db, $this->title)."', description='".mysqli_real_escape_string($db, $this->description)."' WHERE id='".mysqli_real_escape_string($db, $this->id)."'";
			if (!mysqli_query($db, $query)) {
				errorMails($ERROR_EMAILS, mysqli_error($db));
				die();
			}
			foreach ($this->terms as $key => $term) {
				$query = "INSERT INTO `gameTerm` (gameId, term, answer) VALUES ('".mysqli_real_escape_string($db, $this->id)."', '".mysqli_real_escape_string($db, $term->term)."', '".mysqli_real_escape_string($db, $term->answer)."')";
				if (!mysqli_query($db, $query)) {
					errorMails($ERROR_EMAILS, mysqli_error($db));
					die();
				}
			}
		}

		public function loadFromDatabase($db) {
			$query = "SELECT * FROM `games` WHERE id='".mysqli_real_escape_string($db, $this->id)."'";
			if ($result = mysqli_query($db, $query)) {
				$g = mysqli_fetch_assoc($result);
				$this->user = new User();
				$this->user->id = $g["userId"];
				$this->user->loadFromDatabase($db);
				$this->title = $g["title"];
				$this->description = $g["description"];
				$this->dateCreated = $g["dateCreated"];
				$query = "SELECT * FROM `gameTerm` WHERE gameId='".mysqli_real_escape_string($db, $g["id"])."'";
				if ($result = mysqli_query($db, $query)) {
					while ($row = mysqli_fetch_assoc($result)) {
						$term = new gameTerm();
						$term->id = $row["id"];
						$term->term = $row["term"];
						$term->answer = $row["answer"];
						array_push($this->terms, $term);
					}
				}
				else {
					errorMails($ERROR_EMAILS, mysqli_error($db));
					die();
				}
			}
			else {
				errorMails($ERROR_EMAILS, mysqli_error($db));
				die();
			}
		}
	}
?>