<?php

include "../connect.php";

$email = htmlspecialchars(strip_tags($_POST["email"]));
$password = $_POST['password'];

$stmt = $con->prepare("SELECT * FROM `users` WHERE user_email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user) {
    if (password_verify($password, $user['user_password'])) {
        unset($user['user_password']); 
        echo json_encode(["status" => "success", "data" => $user]);
    } else {
        echo json_encode(["status" => "failure", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "failure", "message" => "User not found."]);
}
?>
