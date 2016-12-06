<?php
include_once __DIR__ . "/vendor/autoload.php";
include_once "upload_functions.php";
include_once "google_folders.php";
include_once "authorization.php";

//test folder creation
get_directory ("test/new_folder", $driveService);

?>