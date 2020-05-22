<?php

	include '../Classes/Message.php';
	$message = new Message();
	$result = [];
	$action = $_REQUEST['action'];

	switch ($action) {

		case 'getConversationsList':
			$token = (isset($_POST["Token"]))? $_POST["Token"]: "";
	    	$result = $message->getConversationsList($token);
	     	break;

	     case 'getMessages':
			$token = (isset($_POST["Token"]))? $_POST["Token"]: "";
			$recipient_id = (isset($_POST["Recipient"]))? $_POST["Recipient"]: "";
	    	$result = $message->getMessages($token, $recipient_id);
	     	break;

	     case 'insertMessage':
			$token = (isset($_POST["Token"]))? $_POST["Token"]: "";
			$recipient_id = (isset($_POST["Recipient"]))? $_POST["Recipient"]: "";
			$msg = (isset($_POST["Message"]))? $_POST["Message"]: "";
	    	$result = $message->insertMessage($token, $recipient_id, $msg);
	     	break;

	}

	echo json_encode($result);


