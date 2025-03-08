<?php 

include "../connect.php";

$email = htmlspecialchars(strip_tags($_POST["email"]));
$verify = htmlspecialchars(strip_tags($_POST["verifycode"]));

$stmt = $con->prepare("SELECT * FROM users WHERE user_email = :email AND user_verifycode = :verify");
$stmt->execute([':email' => $email, ':verify' => $verify]);
$count = $stmt->rowCount();

if ($count > 0) {
    $updateStmt = $con->prepare("UPDATE users SET user_approve = '1' WHERE user_email = :email");
    $updateStmt->execute([':email' => $email]);

    echo json_encode(["status" => "success", "message" => "User verified successfully"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Verify code is not correct"]);
}

?>
