<?php

include '../Classes/Notification.php';

$notification = new Notification();
$action = $_GET['action'];
$result = [];

switch($action) {

	case 'getAllNotifications':
		$token = (isset($_POST["Token"]))? $_POST["Token"]: "";
		$result = $notification->getNotifications($token);
  		break;

  	case 'readNotification':
		$id = (isset($_POST["Notification_Id"]))? $_POST["Notification_Id"]: "";
		$result = $notification->readNotification($id);
  		break;

  	case 'readAllNotification':
		$idArray = (isset($_POST["Notification_Id_Array"]))? explode(",", $_POST["Notification_Id_Array"]): "";
		if($idArray[0]!="") {
			$notification->readAllNotifications($idArray);
		}
  		break;

    default:
			$result = "No value";

}

echo json_encode($result);

