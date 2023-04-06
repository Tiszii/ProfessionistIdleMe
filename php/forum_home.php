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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Professionist Idle Forum</title>
</head>

<body>
    <h1 class="neon">FORUM</h1>
    <?php


    if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
        //echo "Ciao" . $_SESSION["logged"];
        if ($_SESSION["logged"] === "true") {
    ?>
            <a class="buttone" href="../php/forum_create.php" style="--clr:#FFFFFF"><span class="buttonespan">&nbsp; NEW POST &nbsp;</span><i class="buttonei"></i></a>
        <?php
        }
        ?>
        <div style="margin-right:10uw; margin-left:10uw; width:69%" class="container">
            <div class="w3-container w3-black">
                <h1>&nbsp;&nbsp;&nbsp;&nbsp; Post</h1>
            </div>

            <?php
            include 'connessione.php';

            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $stmt = $db_connection->prepare("SELECT * FROM post WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $firstrow = mysqli_fetch_assoc($result);
                $testo = $firstrow["testo"];
                $titolo = $firstrow["titolo"];

                $stmt = $db_connection->prepare("SELECT testo, autore FROM commento WHERE id_post= ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
                <div class="w3-container w3-border-top w3-border-bottom w3-border-grey" style="margin:auto; padding: 20px; font-size:35px; text-decoration: none;">
                    <?= $titolo ?>
                </div>
                <div class="w3-container w3-border-top w3-border-bottom w3-border-grey" style="margin:auto; padding: 20px; font-size:15px; text-decoration: none;">
                    <?= $testo ?>
                </div>

                <?php
                if ($result->num_rows > 0) {
                ?>
                    <div class="w3-container w3-black" style="padding: 3px;">
                        <h1 style="font-size:25px;">&nbsp;&nbsp;&nbsp;&nbsp; Commenti</h1>
                    </div>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $autore = $row["autore"];
                        $testo = $row["testo"];
                    ?>

                        <div class="w3-container w3-border-top w3-border-bottom w3-border-grey" style="margin:auto; padding: 20px; font-size:15px; text-decoration: none;">
                            <?= $autore . ":<br>" . $testo ?>
                        </div>
                    <?php
                    }
                }
                $_SESSION["id_post"] = $id;
                if ($_SESSION["logged"] === "true") {
                    ?>
                    <br>
                    <a class="buttone" href="../php/forum_create_c.php" style="--clr:#FFFFFF"><span class="buttonespan">&nbsp; Commenta &nbsp;</span><i class="buttonei"></i></a>
                    <?php
                }
            } else {
                $stmt = $db_connection->prepare("SELECT id, titolo FROM post");
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        $titolo = $row["titolo"];
                    ?>

                        <a class="" href="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?= $id ?>">
                            <div class="w3-container w3-border-top w3-border-bottom w3-border-grey" style="margin:auto; padding: 20px; font-size:25px; text-decoration: none;">

                                <?= $titolo ?>

                            </div>
                        </a>
            <?php
                    }
                }
            }
            ?>
        </div>
    <?php
    } else {
        header("location: index.php");
    }
    ?>

</body>

</html>