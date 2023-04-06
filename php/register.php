<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../img/logo.png" type="image/x-icon" alt="LogoSito">
	<link rel="stylesheet" href="../css/styleregister.css">
	<title>Professionist Idle - Registration</title>
</head>

<?php

session_start();

if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
	if ($_SESSION["logged"] === "true") {
		header("location: index.php");
	} else {
		$boxH = 540;
		$message = "";

		if (isset($_POST["username"])) {
			$username = strtolower(trim(addslashes($_POST["username"])));
		} else {
			$username = "";
		}
		if (isset($_POST["email"])) {
			$email = strtolower(trim(addslashes($_POST["email"])));
		} else {
			$email = "";
		}
		if (isset($_POST["password"])) {
			$password = $_POST["password"];
		} else {
			$password = "";
		}


		if (isset($_POST["username"]) || isset($_POST["email"]) || isset($_POST["password"])) {

			$error = false;

			if (!(preg_match_all("/[a-zA-Z0-9_]{3,20}/", $username) == 1)) {
				$error = true;
				if (strlen($username) > 20 || strlen($username) < 3) {
					$message = "L'username deve essere compreso tra 3 e 20 caratteri";
				} else {
					$message = "L'username può contenere solo caratteri alfanumerici e underscores";
				}
				$boxH = 610;
			} elseif (!preg_match("/[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/", $email)) {
				$error = true;
				$message = "L'email non ha un formato valido";
				$boxH = 610;
			} elseif (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{6,}$/", $password)) {
				$error = true;
				if (strlen($password) < 6) {
					$message = "La password deve contenere almeno 6 caratteri";
				} else {
					$message = "La password deve contenere almeno una lettera maiuscola, una minuscola ed un numero";
				}
				$boxH = 640;
			}

			if ($error) {
				$message = "<br>" . $message;
			} else {

				include 'connessione.php';

				//Controllo email doppia
				$em_dop = $db_connection->prepare("SELECT * FROM utente WHERE email=?");
				$em_dop->bind_param('s', $email); // 's' specifies the variable type => 'string'
				$em_dop->execute();
				$em_dop_res = $em_dop->get_result();


				if ($em_dop_res->num_rows > 0) {
					$message = "Questa email è già registrata";
				} else {
					//Controllo username doppio
					$us_dop = $db_connection->prepare("SELECT * FROM utente WHERE username=?");
					$us_dop->bind_param('s', $username); // 's' specifies the variable type => 'string'
					$us_dop->execute();
					$us_dop_res = $us_dop->get_result();

					if ($us_dop_res->num_rows > 0) {
						$message = "Nome utente non disponibile";
					} else {
						//Funziona
						$stmt = $db_connection->prepare("INSERT INTO utente (email, username, password) " . "VALUES(?, ?, ?)");
						$stmt->bind_param('sss', $email, $username, $password); // 's' specifies the variable type => 'string'
						$stmt->execute();
						$insert = $stmt->get_result();

						$_SESSION["username"] = $username;
						$_SESSION["email"] = $email;
						$_SESSION["logged"] = "true";

						header("location: index.php");
					}
				}

				$message = "<br>" . $message;
				$boxH = 590;
			}
		}
	}
} else {
	header("location: index.php");
}

?>

<body>
	<div class="box" style="--boxH:<?= $boxH ?>px">
		<form autocomplete="off" method="POST" action="">
			<h2>Sign up</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<!--Username must contain only letters, numbers, or 
					underscores, and be between 3 and 20 characters long.-->
				<input type="text" required name="username" value=<?= $username ?>>
				<span>Username</span>
				<i></i>
			</div>
			<div class="inputBox">
				<!--Only one @
					One or more dot
					And if possible check to see if the address after the @ is a valid address-->
				<input type="email" required name="email" value=<?= $email ?>>
				<span>Email</span>
				<i></i>
			</div>
			<div class="inputBox">
				<!-- minimum 6 chars
					at least 1 number
					at least 1 Capital letter-->
				<input type="password" required name="password" value=<?= $password ?>>
				<span>Password</span>
				<i></i>
			</div>
			<div class="links">
				<br>
			</div>
			<input type="submit" value="Register">

			&nbsp;
			<a href="../php/redirect.php">

				<div class="google-btn" style="margin-left: auto; margin-right: auto;">
					<div class="google-icon-wrapper">
						<img class="google-icon" style="width: 20px;" src="../img/logoGoogle.svg" />
					</div>
					<p class="btn-text" style="margin: 6%;"><b>Sign up with google</b></p>
				</div>
			</a>
			<!-- Compiled and minified JavaScript -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>

		</form>
	</div>
</body>

</html>