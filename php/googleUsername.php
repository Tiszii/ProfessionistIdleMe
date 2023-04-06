<?php
session_start();
if ((isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) && (isset($_SESSION["logging"]) && !empty($_SESSION["logging"]))) {
	if ($_SESSION["logged"] === "true" || $_SESSION["logging"] === "false") {
		header("location: index.php");
	} else {
		$boxH = 300;
		$message = "";

		if (isset($_POST["username"])) {
			$username = strtolower(trim(addslashes($_POST["username"])));
		} else {
			$username = "";
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$error = false;

			if (!(preg_match_all("/[a-zA-Z0-9_]{3,20}/", $username) == 1)) {
				$error = true;
				if (strlen($username) > 20 || strlen($username) < 3) {
					$message = "L'username deve essere compreso tra 3 e 20 caratteri";
				} else {
					$message = "L'username può contenere solo caratteri alfanumerici e underscores";
				}
				$boxH = 360;
			} else {
				//se l'utente è già loggato allora lo mando alla pagina index.php altrimenti faccio altri 2 controlli:
				//se l'username è presente nel DB allora non è disponibile altrimenti faccio altri 1 controlli:
				//se la email è presente nel DB mando l'utente alla pagina index.php altrimenti aggiungo il google_id, email e username nel DB e mando l'utente alla pagina index.php e imposto la variabile di sessione a true:

				include 'connessione.php';

				$id = $_SESSION["id"];
				$email = $_SESSION["email"];

				//Controllo username doppio
				$checkUsername = $db_connection->prepare("SELECT username FROM utente WHERE username = ?");
				$checkUsername->bind_param('s', $username);
				$checkUsername->execute();
				$checkUsername = $checkUsername->get_result();
				if ($checkUsername->num_rows > 0) {
					$message = "Nome utente non disponibile";
					$message = "<br>" . $message;
					$boxH = 360;
				} else {
					//Controllo email doppia
					$checkEmail = $db_connection->prepare("SELECT email FROM utente WHERE email = ?");
					$checkEmail->bind_param('s', $email);
					$checkEmail->execute();
					$checkEmail = $checkEmail->get_result();
					if ($checkEmail->num_rows > 0) {
						$_SESSION["logged"] = "true";
						header("location: index.php");
					} else {
						//Aggiungere dati nel database
						$stmt = $db_connection->prepare("INSERT INTO utente (email, username, google_id) VALUES(?, ?, ?)");
						$stmt->bind_param('sss', $email, $username, $id); // 's' specifies the variable type => 'string'
						$stmt->execute();
						$insert = $stmt->get_result();

						if (!$insert->num_rows > 0) {

							$_SESSION["username"] = $username;
							$_SESSION["logged"] = "true";

							header("location: index.php");
						} else {
							$message = "Errore nell'inserimento dei dati";
							$message = "<br>" . $message;
							$boxH = 360;
						}
					}
				}
			}
		}
	}
} else {
	header("location: index.php");
}
?>

<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../img/logo.png" type="image/x-icon" alt="LogoSito">
	<link rel="stylesheet" href="../css/styleregister.css">
	<title>Professionist Idle - Username</title>
</head>

<body>
	<div class="box" style="--boxH:<?= $boxH ?>px">
		<form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<h2>Choose Username</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<input type="text" name="username" required value="<?= $username ?>">
				<span>Username</span>
				<i></i>
			</div>
			<br>
			<input type="submit" value="Confirm">
		</form>
	</div>
</body>

</html>