<?php
include "../connect.php";

$userid = htmlspecialchars(strip_tags($_POST["userid"]));


$stmt = $con->prepare("SELECT * FROM `scannedData` WHERE scanned_userid = ?");
$stmt->execute([$userid]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "success", "data" => $data]);
} else {
    echo json_encode(["status" => "failure", "message" => "Failed to get data"]);
}
?>

