<?php

include '../Classes/Login.php';

$login = new Login();
$result = [];
$action = $_REQUEST['action'];

switch ($action) {

   	case 'login':
   		$username = (isset($_REQUEST["username"])) ? $_REQUEST["username"]: "";
		   $password = (isset($_REQUEST["password"])) ? $_REQUEST["password"]: "";
    	   $result = $login->checkLogin($username, $password);
   		break;

   	case 'verifyCode': 
   		$user_id = (isset($_POST["User_Id"])) ? $_POST["User_Id"]: "";
		   $code = (isset($_POST["Code"])) ? $_POST["Code"]: "";
   		$result = $login->verifyCode($user_id, $code);
   		break;

   	case 'resendCode':
   		$user_id = (isset($_REQUEST["User_Id"])) ? $_REQUEST["User_Id"]: "";
   		$result = $login->resendCode($user_id);
   		break;

      case 'getRole':
         $token = (isset($_REQUEST["Token"])) ? $_REQUEST["Token"]: "";
         $result = $login->getRole($token);
         break;
}

echo json_encode($result);


