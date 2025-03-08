<?php
include "../connect.php";

$userid = htmlspecialchars(strip_tags($_POST["userid"]));
$value = htmlspecialchars(strip_tags($_POST["value"]));



$stmt = $con->prepare("INSERT INTO scannedData(scannedData_value) VALUES (?) WHERE scanned_userid = ?");
$stmt->execute([$value, $userid]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "success", "message" => "Data uploaded successfully"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Failed to upload data"]);
}
?>