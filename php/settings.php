<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon" alt="LogoSito">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Professionist Idle - Settings</title>
</head>

<body>
    <h1 class="neon">SETTINGS</h1>
    <br>
    <?php
    if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
        if ($_SESSION["logged"] === "true") {
            include 'connessione.php';
            $_SESSION["valueSettings"] = "";
            if (isset($_POST["username"])) {
                $_SESSION["valueSettings"] = "username";
                header("Location: ../php/changeSettings.php");
            } else if (isset($_POST["password"])) {
                $_SESSION["valueSettings"] = "password";
                header("Location: ../php/changeSettings.php");
            } else if (isset($_POST["delete"])) {
                $_SESSION["valueSettings"] = "delete";
                header("Location: ../php/changeSettings.php");
            } else if (isset($_POST["Apassword"])) {
                $_SESSION["valueSettings"] = "Apassword";
                header("Location: ../php/changeSettings.php");
            }
        } else {
            header("location: index.php");
        }
    } else {
        header("location: index.php");
    }
    ?>
    <form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div id="button-container">
            <button class="buttone hoveriamo" style="--clr:#1e9bff;" value="username" type="submit" name="username"><span class="buttonespan">&nbsp; Cambia Username &nbsp;</span><i class="buttonei"></i></button>
        </div>
        <br>
        <br>
        <div id="button-container">
            <button class="buttone hoveriamo" style="--clr:#DA291A" value="delete" type="submit" name="delete"><span class="buttonespan">&nbsp; Cancella Account &nbsp;</span><i class="buttonei"></i></button>

        </div>
        <br>
        <br>
        <?php
        try {
            $stmt = $db_connection->prepare("SELECT password FROM utente WHERE username = ?");
            $stmt->bind_param('s', $_SESSION["username"]); // 's' specifies the variable type => 'string'
            $stmt->execute();
            $result = $stmt->get_result();
            $firstrow = mysqli_fetch_assoc($result);
            if (isset($firstrow["password"])) {
        ?>
                <div id="button-container">
                    <button class="buttone hoveriamo" style="--clr:#1e9bff" value="password" type="submit" name="password"><span class="buttonespan">&nbsp; Cambia Password &nbsp;</span><i class="buttonei"></i></button>
                </div>
                <br>
                <br>
            <?php
            } else {
            ?>
                <div id="button-container">
                    <button class="buttone hoveriamo" style="--clr:#1e9bff" value="Apassword" type="submit" name="Apassword"><span class="buttonespan">&nbsp; Aggiungi Password &nbsp;</span><i class="buttonei"></i></button>
                </div>
                <br>
                <br>
        <?php
            }
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }
        ?>
    </form>
    <!-- volume, possibilita di eliminare l'account, cambiare password, cambiare username, linkare account google  -->
    <a href="../php/index.php" class="buttone" style="--clr:#1e9bff"><span class="buttonespan">Back</span><i class="buttonei"></i></a>
</body>

</html>