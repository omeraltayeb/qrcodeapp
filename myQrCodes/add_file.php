<?php
include "../connect.php";

define("MB", 1048576); 


$msgError = "";

$files = imageUpload("../upload/scanned_files", "files", $msgError);

echo json_encode(["status" => "success", "message" => "Data updated successfully"]);


function imageUpload($dir, $imageRequest, &$msgError)
{
    if (!isset($_FILES[$imageRequest])) {
        $msgError = "No file uploaded.";
        return 'empty';
    }

    $imagename  = basename($_FILES[$imageRequest]['name']);
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "jpeg", "png", "gif", "mp3", "pdf", "svg");
    $strToArray = explode(".", $imagename);
    $ext        = strtolower(end($strToArray));

    if (empty($imagename)) {
        $msgError = "File name is empty.";
        return "fail";
    }

    if (!in_array($ext, $allowExt)) {
        $msgError = "Invalid file extension. Allowed extensions: " . implode(", ", $allowExt);
        return "fail";
    }

    if ($imagesize > 2 * MB) {
        $msgError = "File size too large. Maximum size: 2 MB.";
        return "fail";
    }

    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            $msgError = "Failed to create upload directory.";
            return "fail";
        }
    }

    $target_file = $dir . "/" . $imagename;
    if (move_uploaded_file($imagetmp, $target_file)) {
        return $imagename;
    } else {
        $msgError = "Failed to upload file.";
        return "fail";
    }
}