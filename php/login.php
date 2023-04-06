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

if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
	if ($_SESSION["logged"] === "true") {
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
		$boxH = 470;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			// collegamento con il database per la verifica dei dati di login

			include 'connessione.php';

			$stmt = $db_connection->prepare("SELECT * FROM utente WHERE username = ? OR email = ? AND password = BINARY ?");
			$stmt->bind_param('sss', $username, $username, $password); // 's' specifies the variable type => 'string'
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$firstrow = mysqli_fetch_assoc($result);
				$_SESSION["username"] = $firstrow["username"];
				$_SESSION["email"] = $firstrow["email"];
				$_SESSION["logged"] = "true";

				header("location: index.php");
			} else {
				$message = "<br>Dati errati";
				$boxH = 520;
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
			<h2>Sign in</h2>
			<p style="color:red; text-align: center;">
				<?= $message ?>
			</p>
			<div class="inputBox">
				<input type="text" name="username" required value=<?= $username ?>>
				<span>Username / Email</span>
				<i></i>
			</div>
			<div class="inputBox">
				<input type="password" name="password" required value=<?= $password ?>>
				<span>Password</span>
				<i></i>
			</div>
			<div class="links">
				<a href="#">Forgot Password ?</a>
				<a href="register.php">Signup</a>

			</div>
			<input type="submit" value="Login">
			&nbsp;
			<a href="../php/redirect.php">


				<div class="google-btn" style="margin-left: auto; margin-right: auto;">
					<div class="google-icon-wrapper">
						<img class="google-icon" style="width: 20px;" src="../img/logoGoogle.svg" />
					</div>
					<p class="btn-text" style="margin: 6%;"><b>Sign in with google</b></p>
				</div>
			</a>
			<!-- Compiled and minified JavaScript -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
		</form>
	</div>
</body>

</html>