<?php 

include "../connect.php";

$email = htmlspecialchars(strip_tags($_POST["email"]));
$password = password_hash($_POST['userpassword'], PASSWORD_DEFAULT);


 $stmt = $con->prepare("UPDATE users SET user_password = :userpassword WHERE user_email = :email");
    $stmt->execute([':userpassword' => $password, ':email' => $email]);


if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "Failure"]);
}