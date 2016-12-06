<?php
//include google drive api
include_once __DIR__ . '/vendor/autoload.php';

include_once "base.php";

include_once "upload_functions.php";
include_once "google_folders.php";

echo pageHeader("Skladno");

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$oauth_credentials = getOAuthCredentialsFile();

global $client;
$client = new Google_Client();
$client->setAuthConfig($oauth_credentials);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
global $driveService;
$driveService = new Google_Service_Drive($client);
$GLOBALS['service'] = $driveService;

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
<div class="box">
    <?php if (isset($authUrl)): ?>
    <div class="request">
        <a href='<?= $authUrl ?>'>Connect Me!</a>
    </div> 
    <?php endif ?>
</div>