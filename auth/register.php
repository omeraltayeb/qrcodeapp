<?php
include "../connect.php";

define("MB", 1048576);

$username = htmlspecialchars(strip_tags($_POST["username"]));
$email = htmlspecialchars(strip_tags($_POST["email"]));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$verifycode = rand(10000, 99999);
$msgError = ""; 

if (isset($_FILES["profile_image"])) {
    $imagename  = rand(1000, 10000) . $_FILES["profile_image"]['name'];
    $imagetmp   = $_FILES["profile_image"]['tmp_name'];
    $imagesize  = $_FILES["profile_image"]['size'];
    $allowExt = array("jpg", "jpeg", "png", "gif", "svg");
    $strToArray = explode(".", $imagename);
    $ext        = strtolower(end($strToArray));

    if (!empty($imagename) && !in_array($ext, $allowExt)) {
        $msgError = "Invalid file extension.";
    }
    if ($imagesize > 2 * MB) {
        $msgError = "size";
    }
    if (empty($msgError)) {
        $dir = "../upload/profile_image"; 
        move_uploaded_file($imagetmp, $dir . "/" . $imagename);
        $profile_image = $imagename; 
    } else {
        $profile_image = "fail";
    }
} else {
    $profile_image = 'empty';
}

$stmt = $con->prepare("SELECT * FROM `users` WHERE user_email = :email");
$stmt->execute([':email' => $email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "failure", "message" => "email Already exists"]);
} else {
    $data = $con->prepare("INSERT INTO `users`(`username`, `user_email`, `user_password`, `profile_image`, `user_verifycode`) VALUES (:username, :email, :password, :profile_image, :verifycode)");
    $data->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $password,
        ':profile_image' => $profile_image,
        ':verifycode' => $verifycode
    ]);

    $header = "From: support@omdurmanmarkit.shop";
    mail($email, "Verify your email address", "Finish setting up your account, we just need to make sure this email address is yours.\n" .
        "To verify your email address use this security code: $verifycode\n" .
        "If you didn't request this code, you can safely ignore this email. Someone else might have typed your email address by mistake.\n" .
        "Thanks,\n" .
        "The QR Code app account team ", $header);

    echo json_encode(["status" => "success", "message" => "user registered successfully"]);
}
?>
