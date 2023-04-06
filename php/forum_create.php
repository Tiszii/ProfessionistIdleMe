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
		$boxH = 390;
		$titolo = "";
		$testo = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$titolo = $_POST["titolo"];
			$testo = $_POST["testo"];

			include 'connessione.php';

			$stmt = $db_connection->prepare("INSERT INTO post (autore, titolo, testo) VALUES(?, ?, ?)");
			$stmt->bind_param('sss', $_SESSION["username"], $titolo, $testo); // 's' specifies the variable type => 'string'
			$stmt->execute();
			$insert = $stmt->get_result();
			header("location: forum_home.php");
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
			<h2>Nuovo Post</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<input type="text" name="titolo" required value=<?= $titolo ?>>
				<span>Titolo</span>
				<i></i>
			</div>
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