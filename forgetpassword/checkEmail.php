<?php 

include "../connect.php";

$email = htmlspecialchars(strip_tags($_POST["email"]));
$verify = rand(10000, 99999); 


$stmt = $con->prepare("SELECT * FROM users WHERE user_email = :email");
$stmt->execute([':email' => $email]);
$count = $stmt->rowCount();

if ($count > 0) {
    $updateStmt = $con->prepare("UPDATE users SET user_verifycode = :verify WHERE user_email = :email");
    $updateStmt->execute([':verify' => $verify, ':email' => $email]);

     $header = "From: support@omdurmanmarkit.shop";
mail($email, "Verify your email address", "Finish setting up your account, we just need to make sure this email address is yours.\n" .
        "To verify your email address use this security code: $verify\n" .
        "If you didn't request this code, you can safely ignore this email. Someone else might have typed your email address by mistake.\n" .
        "Thanks,\n" .
        "The QR Code app account team ", $header);

    echo json_encode(["status" => "success", "message" => "Verification code resent successfully"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Email not found"]);
}

?>
