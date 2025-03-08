<?php 

include "../connect.php";

$email = htmlspecialchars(strip_tags($_POST["email"]));
$verify = rand(10000, 99999); 


$updateStmt = $con->prepare("UPDATE users SET user_verifycode = :verify WHERE user_email = :email");
$isUpdated = $updateStmt->execute([':verify' => $verify, ':email' => $email]);

if ($isUpdated) {
  $header = "From: support@omdurmanmarkit.shop";
mail($email, "Verify your email address", "Finish setting up your account, we just need to make sure this email address is yours.\n" .
        "To verify your email address use this security code: $verify\n" .
        "If you didn't request this code, you can safely ignore this email. Someone else might have typed your email address by mistake.\n" .
        "Thanks,\n" .
        "The QR Code app account team ", $header);

    echo json_encode(["status" => "success", "message" => "Verification code resent successfully"]);
} else {
    echo json_encode(["status" => "failure", "message" => "Failed to update verification code"]);
}



?>
