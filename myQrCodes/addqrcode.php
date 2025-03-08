<?php

include "../connect.php";

define("MB", 1048576); 

$labelName = htmlspecialchars(strip_tags($_POST["labelName"]));
$userid = htmlspecialchars(strip_tags($_POST["userid"]));
$msgError = "";

$qr_codes_image = imageUpload("../upload/qr_codes_images", "qr_codes_image", $msgError);

if ($qr_codes_image !== "fail" && $qr_codes_image !== 'empty') {
    $stmt = $con->prepare("INSERT INTO `myQrCodes`(`myQrCodes_labelName`, `myQrCodes_imagePath`, `myQrCodes_userid`) 
                           VALUES (:labelName, :qr_codes_image, :userid)");
    $stmt->execute([
        ':labelName' => $labelName,
        ':qr_codes_image' => $qr_codes_image,
        ':userid' => $userid, 
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Data uploaded successfully"]);
    } else {
        echo json_encode(["status" => "failure", "message" => "Data not uploaded"]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => $msgError]);
}





function imageUpload($dir, $imageRequest, &$msgError)
{
    if (!isset($_FILES[$imageRequest])) {
        $msgError = "No file uploaded.";
        return 'empty';
    }

    // Get the original file name and extension
    $originalName = basename($_FILES[$imageRequest]['name']);
    $fileInfo = pathinfo($originalName);
    $fileName = $fileInfo['filename']; // File name without extension
    $fileExt = $fileInfo['extension']; // File extension

    // Generate a unique file name with the random number before the extension
    $imagename  = $fileName . '_' . rand(1000, 10000) . '.' . $fileExt;
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "jpeg", "png", "gif", "mp3", "pdf", "svg");

    // Validate the file name
    if (empty($imagename)) {
        $msgError = "File name is empty.";
        return "fail";
    }

    // Validate the file extension
    if (!in_array(strtolower($fileExt), $allowExt)) {
        $msgError = "Invalid file extension. Allowed extensions: " . implode(", ", $allowExt);
        return "fail";
    }

    // Validate the file size
    if ($imagesize > 2 * MB) {
        $msgError = "File size too large. Maximum size: 2 MB.";
        return "fail";
    }

    // Create the upload directory if it doesn't exist
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            $msgError = "Failed to create upload directory.";
            return "fail";
        }
    }

    // Move the uploaded file to the target directory
    $target_file = $dir . "/" . $imagename;
    if (move_uploaded_file($imagetmp, $target_file)) {
        return $imagename;
    } else {
        $msgError = "Failed to upload image.";
        return "fail";
    }
}