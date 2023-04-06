<?php
if (isset($_SESSION["logged"])&& !empty($_SESSION["logged"])) {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'my_professionistidle';

    try {
        $db_connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($db_connection == null)
            throw new exception(mysqli_connect_error() . ' Error n.' . mysqli_connect_errno());
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
} else {
    header("location: index.php");
}
