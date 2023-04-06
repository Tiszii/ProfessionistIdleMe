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
    <link rel="stylesheet" type="text/css" href="../css/styleregister.css">

    <title>Professionist Idle - Settings</title>
</head>

<body>
    <?php
    if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
        if ($_SESSION["logged"] === "true") {
            if (!isset($_SESSION["valueSettings"])) {
                header("location: index.php");
            } else {
                $boxH = 460;
                $message = "";
                include 'connessione.php';
                if ($_SESSION["valueSettings"] == "password") {
                    $password = "";
                    $NewPassword = "";
                    $ConfirmPassword = "";
                    $message = "";
                    $boxH = 480;
                    if (isset($_POST["password"]) && isset($_POST["NewPassword"]) && isset($_POST["ConfirmPassword"])) {
                        $password = $_POST["password"];
                        $NewPassword = $_POST["NewPassword"];
                        $ConfirmPassword = $_POST["ConfirmPassword"];
                        $message = "";

                        if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{6,}$/", $NewPassword)) {
                            $error = true;
                            if (strlen($NewPassword) < 6) {
                                $message = "La password deve contenere almeno 6 caratteri";
                                $boxH = 520;
                            } else {
                                $message = "La password deve contenere almeno una lettera maiuscola, una minuscola ed un numero";
                                $boxH = 550;
                            }
                        } else {
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                //connessione al database per verificare la password
                                try {
                                    $stmt = $db_connection->prepare("SELECT * FROM utente WHERE password = ? AND Username = ?");
                                    $stmt->bind_param('ss', $password, $_SESSION["username"]); // 's' specifies the variable type => 'string'
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($NewPassword == $ConfirmPassword && $result->num_rows > 0) {

                                        $stmt = $db_connection->prepare("UPDATE utente SET password = ? WHERE Username = ? ");
                                        $stmt->bind_param('ss', $NewPassword, $_SESSION["username"]); // 's' specifies the variable type => 'string'
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        unset($_SESSION["valueSettings"]);
                                        header("Location: settings.php");
                                    } else {
                                        $message = "Password errata o le password non coincidono";
                                    }
                                } catch (Exception $e) {
                                    $message = "Errore nel cambiamento della password";
                                }
                            } else {
                                $message = "Complila tutti i campi";
                            }
                        }
                    }

    ?>
                    <div class="box" style="--boxH:<?= $boxH ?>px">
                        <form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <h2>Change Password</h2>
                            <p style="color:red; text-align: center;">
                                <?= $message ?>
                            </p>
                            <div class="inputBox">
                                <input type="password" name="password" required value=<?= $password ?>>
                                <span>Password</span>
                                <i></i>
                            </div>
                            <div class="inputBox">
                                <input type="password" name="NewPassword" required value=<?= $NewPassword ?>>
                                <span>NewPassword</span>
                                <i></i>
                            </div>
                            <div class="inputBox">
                                <input type="password" name="ConfirmPassword" required value=<?= $ConfirmPassword ?>>
                                <span>ConfirmPassword</span>
                                <i></i>
                            </div>
                            &nbsp;
                            <input type="submit" value="Cambia">
                        </form>
                    </div>
                <?php

                } else if ($_SESSION["valueSettings"] == "username") {
                    $username = "";
                    $boxH = 300;
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST["username"])) {
                            $username = $_POST["username"];
                            $message = "";
                            if (!(preg_match_all("/[a-zA-Z0-9_]{3,20}/", $username) == 1)) {
                                $error = true;
                                if (strlen($username) > 20 || strlen($username) < 3) {
                                    $message = "L'username deve essere compreso tra 3 e 20 caratteri";
                                    $boxH = 360;
                                } else {
                                    $message = "L'username può contenere solo caratteri alfanumerici e underscores";
                                    $boxH = 350;
                                }
                            } else {
                                try {
                                    $stmt = $db_connection->prepare("SELECT * FROM utente WHERE username = ?");
                                    $stmt->bind_param('s', $username); // 's' specifies the variable type => 'string'
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $message = "Username non disponibile";
                                    } else {
                                        $stmt = $db_connection->prepare("UPDATE utente SET username = ? WHERE username = ?");
                                        $stmt->bind_param('ss', $username, $_SESSION["username"]); // 's' specifies the variable type => 'string'
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if (!$result->num_rows > 0) {
                                            $_SESSION["username"] = $username;
                                            unset($_SESSION["valueSettings"]);
                                            header("Location: settings.php");
                                        } else {
                                            $message = "Errore nel cambiamento dell'username";
                                        }
                                    }
                                } catch (Exception $e) {
                                    $message = "Errore nel cambiamento dell'username";
                                }
                            }
                        }
                    }

                ?>
                    <div class="box" style="--boxH:<?= $boxH ?>px">
                        <form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <h2>Change Username</h2>
                            <p style="color:red; text-align: center;">
                                <?= $message ?>
                            </p>
                            <div class="inputBox">
                                <input type="text" name="username" required value=<?= $username ?>>
                                <span>Nuovo Username</span>
                                <i></i>
                            </div>
                            &nbsp;
                            <input type="submit" value="Cambia">
                        </form>
                    </div>
                <?php
                } else if ($_SESSION["valueSettings"] == "delete") {
                    $paroleItaliane = array(
                        "casa", "caffè", "macchina", "mare", "montagna", "sole", "albero", "gatto", "cane", "bicicletta",
                        "cioccolato", "tartaruga", "libro", "giornale", "maglia", "cappello", "scarpe", "occhiali", "pioggia", "neve",
                        "sole", "musica", "chitarra", "pianoforte", "bicchiere", "tazza", "cucchiaio", "forchetta", "coltello", "moto",
                        "aereo", "treno", "bicicletta", "computer", "telefono", "televisione", "frigorifero", "cucina", "pentola", "piatto",
                        "tovaglia", "panino", "pizza", "hamburger", "insalata", "pasta", "riso", "pesce", "carne", "pollo",
                        "uovo", "formaggio", "pane", "burro", "olio", "sale", "pepe", "limone", "arancia", "mela",
                        "banana", "fragola", "ciliegia", "ananas", "melone", "anguria", "zucchero", "farina", "latte", "yogurt",
                        "burro", "uva", "vino", "birra", "acqua", "caffè", "tè", "cioccolata", "caramella", "biscotto",
                        "torta", "gelato", "ciambella", "merenda", "pranzo", "cena", "colazione", "vacanza", "viaggio", "spiaggia",
                        "montagna", "campagna", "città", "parco", "piscina", "palestra", "teatro", "cinema", "museo", "libreria",
                        "università", "ufficio", "negozio", "mercato", "supermercato", "farmacia", "ospedale", "medico", "dentista", "avvocato",
                        "insegnante", "studente", "impiegato", "operaio", "artista", "musicista", "scrittore", "giornalista", "architetto", "cuoco",
                        "cameriere", "autista", "pilota", "marinaio", "soldato"
                    );
                    $parolaCasuale = $paroleItaliane[rand(0, count($paroleItaliane) - 1)];

                    $parola = "";
                    $boxH = 340;
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST["parola"])) {
                            $parola = $_POST["parola"];
                            $message = "";
                            if ($_SESSION["parolaCasuale"] !== $parola) {
                                $message = "Parola scritta non correttamente";
                                $boxH = 360;
                            } else {
                                try {
                                    $stmt = $db_connection->prepare("DELETE FROM utente WHERE username = ?");
                                    $stmt->bind_param('s', $_SESSION["username"]); // 's' specifies the variable type => 'string'
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if (!$result->num_rows > 0) {
                                        session_destroy();
                                        unset($_SESSION["valueSettings"]);
                                        header("Location: settings.php");
                                    } else {
                                        $message = "Errore nell'eliminazione dell'account";
                                    }
                                } catch (Exception $e) {
                                    $message = "Errore nell'eliminazione dell'account";
                                }
                            }
                        }
                    }
                    $_SESSION["parolaCasuale"] = $parolaCasuale;
                ?>
                    <div class="box" style="--boxH:<?= $boxH ?>px">
                        <form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <h2>Delete Account</h2>
                            <h4 style="color:white; text-align:center">Scrivi la parola <u style="color: red"><?= $parolaCasuale ?></u> per eliminare l'account</h4>
                            <p style="color:red; text-align: center;">
                                <?= $message ?>
                            </p>
                            <div class="inputBox">
                                <input type="text" name="parola" required value=<?= $parola ?>>
                                <span>Parola</span>
                                <i></i>
                            </div>
                            &nbsp;
                            <input type="submit" value="Elimina">
                        </form>
                    </div>
                <?php
                } else if ($_SESSION["valueSettings"] == "Apassword") {
                    $password = "";
                    $message = "";
                    $boxH = 310;
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST["password"])) {
                            $password = $_POST["password"];
                            $message = "";
                            if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{6,}$/", $password)) {
                                $error = true;
                                if (strlen($password) < 6) {
                                    $message = "La password deve contenere almeno 6 caratteri";
                                    $boxH = 350;
                                } else {
                                    $message = "La password deve contenere almeno una lettera maiuscola, una minuscola ed un numero";
                                    $boxH = 365;
                                }
                            } else {
                                //connessione al database per verificare la password
                                try {
                                    $stmt = $db_connection->prepare("UPDATE utente SET password = ? WHERE username = ?");
                                    $stmt->bind_param('ss', $password, $_SESSION["username"]); // 's' specifies the variable type => 'string'
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    unset($_SESSION["valueSettings"]);
                                    header("Location: settings.php");
                                } catch (Exception $e) {
                                    $message = "Errore nell'aggiunta della password";
                                }
                            }
                        }
                    }

                ?>
                    <div class="box" style="--boxH:<?= $boxH ?>px">
                        <form autocomplete="off" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <h2>Add Password</h2>
                            <p style="color:red; text-align: center;">
                                <?= $message ?>
                            </p>
                            <div class="inputBox">
                                <input type="password" name="password" required value=<?= $password ?>>
                                <span>Password</span>
                                <i></i>
                            </div>
                            &nbsp;
                            <input type="submit" value="Aggiungi">
                        </form>
                    </div>
    <?php
                }
            }
        } else {
            header("location: index.php");
        }
    } else {
        header("location: index.php");
    }

    ?>
    <!-- volume, linkare account google  -->
    <a href="../php/settings.php" class="buttone" style="--clr:white"><span class="buttonespan">Back</span><i class="buttonei"></i></a>
</body>

</html>