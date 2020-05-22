<?php

include '../Classes/Trail.php';

$trail = new Trail();
$action = $_GET['action'];
$result = [];

switch($action) {
	case 'getTrailsById':
		$ticket_id = (isset($_POST["Ticket_Id"]))? $_POST["Ticket_Id"]: "";
		$result = $trail->getTrailsById($ticket_id);
  		break;

    default:
			$result = "No value";
}
echo json_encode($result);

