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
	<title>Professionist Idle - Login</title>
</head>
<?php


if ((isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) && (isset($_SESSION["logging"]) && !empty($_SESSION["logging"]))) {
	if ($_SESSION["logged"] === "true" || $_SESSION["logging"] === "false") {
		header("location: index.php");
	} else {
		if (isset($_POST["username"])) {
			$username = trim(addslashes($_POST["username"]));
		} else {
			$username = "";
		}
		if (isset($_POST["password"])) {
			$password = $_POST["password"];
		} else {
			$password = "";
		}

		$message = "";
		$boxH = 380;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			// collegamento con il database per la verifica dei dati di login

			include 'connessione.php';


			if ($_SESSION["usernameToLog"] != $username) {
				$message = "<br>Dati errati";
				$boxH = 420;
			} else {

				$stmt = $db_connection->prepare("SELECT * FROM utente WHERE username = ? AND password = BINARY ?");
				$stmt->bind_param('ss', $username, $password); // 's' specifies the variable type => 'string'
				$stmt->execute();
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
					$firstrow = mysqli_fetch_assoc($result);
					$_SESSION["username"] = $firstrow["username"];
					$_SESSION["email"] = $firstrow["email"];

					$stmt = $db_connection->prepare("UPDATE utente SET google_id = ? WHERE email = ?");
					$stmt->bind_param('ss', $_SESSION["id"], $_SESSION["email"]); // 's' specifies the variable type => 'string'
					$stmt->execute();
					$result = $stmt->get_result();
					unset($_SESSION["usernameToLog"]);
					$_SESSION["logged"] = "true";

					header("location: index.php");
				} else {
					$message = "<br>Dati errati";
					$boxH = 420;
				}
			}
		}
	}
} else {
	header("location: index.php");
}

?>

<body>
	<div class="box" style="--boxH:<?= $boxH ?>px">
		<form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<h2>Link your account</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<input type="text" name="username" required value=<?= $username ?>>
				<span>Username</span>
				<i></i>
			</div>
			<div class="inputBox">
				<input type="password" name="password" required value=<?= $password ?>>
				<span>Password</span>
				<i></i>
			</div>
			&nbsp;
			<input type="submit" value="Login">
		</form>
	</div>
</body>

</html>