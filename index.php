<?php
//include google drive api
include_once __DIR__ . '/vendor/autoload.php';

include_once "base.php";

include_once "upload_functions.php";
include_once "google_folders.php";

echo pageHeader("Skladno");

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$oauth_credentials = getOAuthCredentialsFile();

$client = new Google_Client();
$client->setAuthConfig($oauth_credentials);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);
setcookie("client", json_encode($client));

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['upload_token']);
}

if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);
  $_SESSION['upload_token'] = $token;
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

if (
  !empty($_SESSION['upload_token'])) {
  $client->setAccessToken($_SESSION['upload_token']);
  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
  }
} else {
  $authUrl = $client->createAuthUrl();
}

$client->getAccessToken();
?> 

<link href="css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="dropzone.js"></script>
<div class="box">
    <?php if (isset($authUrl)): ?>
    <div class="request">
        <a href='<?= $authUrl ?>'>Connect Me!</a>
    </div>
    <?php else: ?>
        <form action="upload.php" class="dropzone"></form>
    <?php endif ?>
</div>