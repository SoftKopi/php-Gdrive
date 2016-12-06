<?php
//connect google api
include_once __DIR__ . "/vendor/autoload.php";
include_once "authorization.php";

//search folder and create it if it doesn't exist
function search_directory ($folder_name, $folder_parent_id, $driveService) {
    $pageToken = null;
    do {
        $response = $driveService->files->listFiles(array(
            'q' => "title='$folder_name' and '$folder_parent_id' in parents",
            'spaces' => 'drive',
            'pageToken' => $pageToken,
            'fields' => 'nextPageToken, id',
        ));
    } while ($pageToken != null);

    if ($response->id=="") {
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $folder_name,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($folder_parent_id)));
        $file = $driveService->files->create($fileMetadata, array(
            'fields' => 'id'));
    }
    return $response->id;
}

//get folder Id for upload;
function get_directory ($folder_path, $driveService) {
    $folders = explode('/', $folder_path);
    $folder_id = "0ABdFR4K6P8E3Uk9PVA"; // input root id of new profile;
    for ($folders_counter = 0; $folders_counter < count($folders); $folders_counter++) {
        $folder_id = search_directory($folders[$folders_counter], $folder_id, $driveService);
    };
    return $folder_id;
}
?>