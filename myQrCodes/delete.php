<?php
include "../connect.php";

// Get the record ID to delete
$recordId = htmlspecialchars(strip_tags($_POST["recordId"]));


// Fetch the file path from the database
$stmt = $con->prepare("SELECT `myQrCodes_imagePath` FROM `myQrCodes` WHERE `myQrCodes_id` = :recordId");
$stmt->execute([':recordId' => $recordId]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    echo json_encode(["status" => "failure", "message" => "Record not found"]);
    exit;
}

// Get the file path
$filePath = "../upload/qr_codes_images/" . $record['myQrCodes_imagePath'];

// Delete the file from the server
if (file_exists($filePath)) {
    if (!unlink($filePath)) {
        echo json_encode(["status" => "failure", "message" => "Failed to delete file"]);
        exit;
    }
} else {
    echo json_encode(["status" => "failure", "message" => "File not found"]);
    exit;
}

// Delete the record from the database
$stmt = $con->prepare("DELETE FROM `myQrCodes` WHERE `myQrCodes_id` = :recordId");
$stmt->execute([':recordId' => $recordId]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "success", "message" => "Record and file deleted successfully"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Failed to delete record"]);
}