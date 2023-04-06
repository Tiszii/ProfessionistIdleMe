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
        <title>Professionist Idle</title>
</head>

<body>
        <h1 class="neon">PROFESSIONISTS IDLE</h1>
        <br>
        <?php
        if ((isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) && $_SESSION["logged"] === "true") {
        ?>
                <a class="buttone" href="../php/gioco.php" style="--clr:#1e9bff"><span class="buttonespan">&nbsp; Play &nbsp;</span><i class="buttonei"></i></a>
                <a class="buttone" href="forum_home.php" style="--clr:#ff1867"><span class="buttonespan">&nbsp;Forum&nbsp;</span><i class="buttonei"></i></a>
                <a class="buttone" href="../php/settings.php" style="--clr:#DA291A"><span class="buttonespan">&nbsp;Settings&nbsp;</span><i class="buttonei"></i></a>
                <a class="buttone" href="../php/close_session.php" style="--clr:#FFE017"><span class="buttonespan">&nbsp;Logout&nbsp;</span><i class="buttonei"></i></a>
        <?php
        } else {
                $_SESSION["logged"] = "false";
        ?>
                <a class="buttone" href="../php/login.php" style="--clr:#1e9bff"><span class="buttonespan">&nbsp; Play &nbsp;</span><i class="buttonei"></i></a>
                <a class="buttone" href="forum_home.php" style="--clr:#ff1867"><span class="buttonespan">&nbsp;Forum&nbsp;</span><i class="buttonei"></i></a>
        <?php
        }
        ?>

</body>

</html>