<?php
//link google drive api
include_once __DIR__ . "/vendor/autoload.php";

//sruct to get rules for file_upload
class upload_info {
    public $folder_name;
    public $reg_expr_array;
}

//get rules from txt
function get_rules ($rules_file_path) {
    $rules = array();
    $rules_counter = 0;
    $rules_file = fopen($rules_file_path, "r") or die("Rules file doesn't exist or damaged.");
    while(!feof($rules_file)) {
        $rules[$rules_counter] = new upload_info;
        $buff = fgets($rules_file);
        list($rules[$rules_counter]->folder_name, $temp_reg_expr_stmnt) = explode(", ", $buff, 2);
        $rules[$rules_counter]->reg_expr_array = explode(';',$temp_reg_expr_stmnt);
        $rules_counter++;
    }
    fclose($rules_file);
    return $rules;
}

//upload to Google Drive
function upload_on_cloud ($file_to_upload, $file_name, $folderId) {
    $file = new Google_Service_Drive_DriveFile(array(
        'name' => $file_name,
        'parents' => array($folderId)
    ));
    $result = $driveService->files->create(
        $file,
        array(
            'data' => file_get_contents($file_to_upload),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'media'
        )
    );
}

//check file name
function check_file_name ($reg_expr, $file_name)
{
    for ($reg_expr_counter=0; $reg_expr_counter<count($reg_expr); $reg_expr_counter++)
        if(preg_match($reg_expr[$reg_expr_counter],$file_name)) return 1;
    return 0;
}
?>