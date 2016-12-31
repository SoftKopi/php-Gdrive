<?php
include_once ("authorization.php");

function download_directory ($folderId, $folder_name, $driveService, $folder_path) {
    if ($folder_path=="") {
        $folder_path = __DIR__ . "\\downloads\\$folder_name";
        if (!file_exists($folder_path)) {
            mkdir(__DIR__ . "\\downloads\\$folder_name", 0777, true);
        }
    }
    
    //get list of files
    $pageToken = null;
    do {
        $response = $driveService->files->listFiles(array(
            'q' => "'$folderId' in parents",
            'spaces' => 'drive',
            'pageToken' => $pageToken,
            'fields' => 'nextPageToken, files(id, mimeType, name)',
        ));
        echo "Getting list <br \>";
    } while ($pageToken != null);

    //use info
    foreach ($response->files as $file) {
        //if file is folder
        if ($file->mimeType == 'application/vnd.google-apps.folder') {
            $new_folder_name = $file->name;
            $new_folder_path = "$folder_path\\$new_folder_name";
            $new_folder_id = $file->id;
            //create new folder in /downloads
            if (!file_exists($new_folder_path)) {
                mkdir($new_folder_path, 0777, true);
            }
            //start downloading new folder
            download_directory($new_folder_id, $new_folder_name, $driveService, $new_folder_path);
        }
        //else download file in working directory
        else {
            echo "Here is JOHNY, file id:  $file->id <br \>";
            $file_to_download_id = $file->id;
            $content = $driveService->files->get($file_to_download_id, array(
                'alt' => 'media' ));
            $outHandle = fopen("$folder_path\\$file->name", "w+");
            while (!$content->getBody()->eof()) {
                fwrite($outHandle, $content->getBody()->read(1024));
            }
            fclose($outHandle);
        }
    }
}

//let's test this, folderID belongs to "test" folder in my group googledrive account
download_directory("0BxdFR4K6P8E3eHRPTmo2aU81VU0", "test", $driveService, "");

?>