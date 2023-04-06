<?php
//start the session
session_start();

if (isset($_SESSION["logged"])&& !empty($_SESSION["logged"])) {
  echo "la variabile di sessione logged è settata<br>";
  echo "la variabile di sessione logged vale: " . $_SESSION["logged"] . "<br>";
  if ($_SESSION["logged"] === "true") {
    echo $_SESSION["email"] . "<br>";
    echo $_SESSION["username"] . "<br>";
    echo $_SESSION["id"] . "<br>";
  } else {
      header("location: index.php");
  }
} else {
  header("location: index.php");
}
