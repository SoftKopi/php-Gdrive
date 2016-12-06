<?php
//include all stuff we need
include_once __DIR__ . "/vendor/autoload.php";
include_once "google_folders.php";
include_once "upload_functions.php";

$trashdir = '/trash/';
$trash_file = $trashdir . basename($_FILES['userfile']['name']);

if (!empty($_FILES)) {
    $temp_file = $_FILES['userfile']['tmp_name'];
    $temp_file_name = $_FILES['userfile']['name'];
    $rules = get_rules("rules.txt");
    if(check_file_name($rules[0]->reg_expr_array, $temp_file_name)==1){
        upload_on_cloud($temp_file, $temp_file_name, get_directory($rules[0]->folder_name));
    }
    move_uploaded_file($_FILES['userfile']['tmp_name'], $trash_file);
    unlink($trash_file);
}
?>