<?php
session_start();
if (isset($_SESSION["logged"]) && !empty($_SESSION["logged"])) {
    if ($_SESSION["logged"] === "true") {
        session_destroy();
    }
}
header("location: index.php");
