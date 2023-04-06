<?php
session_start();


if (isset($_SESSION["logged"])&& !empty($_SESSION["logged"])) {
  if ($_SESSION["logged"] === "true") {
    header("location: index.php");
  } else {
    // Scaricare 8.0: https://github.com/googleapis/google-api-php-client/releases
    //require_once '../google-api-php-client--PHP8.0/vendor/autoload.php';
    // require_once '../database/dbConnection.php';
    if (!@include("../google-api-php-client--PHP8.0/vendor/autoload.php")) {
      echo ("<h1>Scaricare 8.0 e mettere nella cartella del progetto: https://github.com/googleapis/google-api-php-client/releases</h1>");
    } else {

      require_once '../google-api-php-client--PHP8.0/vendor/autoload.php';

      $clientID = '675115182043-0qopb8q1v6j9e596tafm1nueu7daqpf5.apps.googleusercontent.com';
      $clientSecret = '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';
      $redirectUri = 'http://localhost/php/redirect.php';

      // create Client Request to access Google API
      $client = new Google_Client();
      $client->setClientId($clientID);
      $client->setClientSecret($clientSecret);
      $client->setRedirectUri($redirectUri);
      $client->addScope("email");
      $client->addScope("profile");

      // authenticate code from Google OAuth Flow
      if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $_SESSION["email"] = strtolower($google_account_info->email);
        $_SESSION["id"] = $google_account_info->id;
        $email = strtolower($_SESSION["email"]);

        //connessione con il DB per verificare che l'utente non sia gia presente
        include 'connessione.php';


        $stmt = $db_connection->prepare("SELECT * FROM utente WHERE email = ?");
        $stmt->bind_param('s', $email); // 's' specifies the variable type => 'string'
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $firstrow = mysqli_fetch_assoc($result);
          if ($_SESSION["id"] == $firstrow["google_id"]) {
            $_SESSION["username"] = $firstrow["username"];
            $_SESSION["logged"] = "true";
            header("location: index.php");
          } else {
            $_SESSION["usernameToLog"] = $firstrow["username"];
            $_SESSION["logging"]= "true";
            header("location: link.php");
          }
        } else {
          $_SESSION["logging"]= "true";
          header("location: googleUsername.php");
        }
      } else {
          header("location: " . $client->createAuthUrl());
      }
    }
  }
} else {
  header("location: index.php");
}
