<?php
include_once "upload_functions.php";
include_once "google_folders.php";

$temp_file_name = "rules.txt";
$result = get_rules($temp_file_name);
var_dump($result);
echo "<br />";
echo $result[0]->reg_expr_array[0];
var_dump(check_file_name($result[0]->reg_expr_array, $temp_file_name));

echo get_directory ($result[0]->folder_name);
?>