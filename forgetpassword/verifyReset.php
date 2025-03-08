<?php 

include "../connect.php" ;

$email = htmlspecialchars(strip_tags($_POST["email"]));
$verify = htmlspecialchars(strip_tags($_POST["verifycode"])); 

$stmt = $con->prepare("SELECT users_id FROM users WHERE users_email = :email AND users_verfiycode = :verifycode"); 
$stmt->execute([
    ':email' => $email,
    ':verifycode' => $verify
]); 

$count = $stmt->rowCount(); 
if($count > 0){
    echo json_encode(["status" => "success"]); 

} else{
    echo json_encode(["status" => "failure"]); 

}
?>
