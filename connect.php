<?php
$dsn = "mysql:host=pdb1035.awardspace.net;dbname=4500097_qrcodeapp";
$user = "4500097_qrcodeapp";
$pass = "P[{(V:pr28JzeCH0";
$option = array(
   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
);
$countrowinpage = 9;
try {
   $con = new PDO($dsn, $user, $pass, $option);
   $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Access-Control-Allow-Origin");
   header("Access-Control-Allow-Methods: POST, OPTIONS , GET");
   if (!isset($notAuth)) {
      // checkAuthenticate();
   }
} catch (PDOException $e) {
   echo $e->getMessage();
}