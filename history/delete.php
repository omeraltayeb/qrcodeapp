<?php
include "../connect.php";

$id = htmlspecialchars(strip_tags($_POST["id"]));


$stmt = $con->prepare("DELETE FROM `scannedData` WHERE scannedData_id = ?");
$stmt->execute([$id]);
if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Failed to delete data"]);
}
?>

