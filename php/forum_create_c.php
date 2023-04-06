<?php
session_start();
?>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../img/logo.png" type="image/x-icon" alt="LogoSito">
	<link rel="stylesheet" href="../css/styleregister.css">
	<title>Professionist Idle Forum</title>
</head>
<?php

if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
	if ($_SESSION["logged"] === "true") {
		$message = "";
		$boxH = 320;
		$testo = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$testo = $_POST["testo"];

			include 'connessione.php';

			// c'è un giga problema mi sa che se l'utente elimina la sessione in qualche modo bizzarro riesche a rompere tutto siuu

			if (isset($_SESSION["id_post"])) {
				$stmt = $db_connection->prepare("INSERT INTO commento (testo, autore, id_post) VALUES(?, ?, ?)");
				$stmt->bind_param('ssi', $testo, $_SESSION["username"], $_SESSION["id_post"]);
				$stmt->execute();
				$insert = $stmt->get_result();
				header("location: forum_home.php");
			} else {
				$message = "errore!";
			}
		}
	} else {
		header("location: index.php");
	}
} else {
	header("location: index.php");
}

?>

<body>
	<div class="box" style="--boxH:<?= $boxH ?>px">
		<form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<h2>Nuovo Commento</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<input type="text" name="testo" required value=<?= $testo ?>>
				<span>Testo</span>
				<i></i>
			</div>
			&nbsp;
			<input type="submit" value="Conferma">
		</form>
	</div>
</body>

</html>