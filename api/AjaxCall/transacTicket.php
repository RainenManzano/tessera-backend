<?php

include '../Classes/Ticket.php';

$ticket = new Ticket();
$action = $_REQUEST['action'];
$result = [];

switch($action) {

	case 'addTicket':
		$token = $_POST["Token"];
		$data =[
		 	'Issue' => isset($_POST['Issue']) ? $_POST['Issue'] : '',
            'Description' => isset($_POST['Description']) ? $_POST['Description'] : '',
            'Category_Id' => isset($_POST['Category_Id']) ? $_POST['Category_Id'] : ''
		];
		$level = isset($_POST['Level']) ? $_POST['Level'] : '';
		$result = $ticket->addTicket($token, $data, $level);
		break;

	case 'getAllTickets':
		$result = $ticket->getAllTickets();
		// print_r($result);
  		break;

  	case 'getSupportedTickets':
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$result = $ticket->getSupportedTickets($token);
		break;

	case 'getIssuedTickets':
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$result = $ticket->getIssuedTickets($token);
		break;

	case 'getSingleTicket':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$result = $ticket->getSingleTicket($id);
		break;

	case 'getTotalTickets':
		$type = isset($_REQUEST['Type']) ? $_REQUEST['Type'] : die;
		$filteredDate = isset($_REQUEST['Filtered_Date']) ? $_REQUEST['Filtered_Date'] : die;
		$token = isset($_REQUEST['Token']) ? $_REQUEST['Token'] : die;
		$result = $ticket->getTotalTickets($filteredDate, $type, $token);
		break;

	case 'getTicketComments':
		$ticketId = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$result = $ticket->getTicketComments($ticketId);
		break;

	case 'updateTicket':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : "";
		$data =[
		 	'Issue' => isset($_POST['Issue']) ? $_POST['Issue'] : '',
            'Description' => isset($_POST['Description']) ? $_POST['Description'] : '',
            'Solution' => isset($_POST['Solution']) ? $_POST['Solution'] : '',
            'CreatedBy' => isset($_POST['CreatedBy']) ? $_POST['CreatedBy'] : '',
            'SupportedBy' => isset($_POST['SupportedBy']) ? $_POST['SupportedBy'] : '',
            'DateCreated' => isset($_POST['DateCreated']) ? $_POST['DateCreated'] : '',
            'HourCreated' => isset($_POST['HourCreated']) ? $_POST['HourCreated'] : '',
            'MinuteCreated' => isset($_POST['MinuteCreated']) ? $_POST['MinuteCreated'] : '',
            'DateClosed' => isset($_POST['DateClosed']) ? $_POST['DateClosed'] : '',
            'HourClosed' => isset($_POST['HourClosed']) ? $_POST['HourClosed'] : '',
            'MinuteClosed' => isset($_POST['MinuteClosed']) ? $_POST['MinuteClosed'] : '',
            'Status' => isset($_POST['Status']) ? $_POST['Status'] : ''
		];
		$data["SupportedBy"] = ($data["SupportedBy"]=='null')? null: $data["SupportedBy"];
		$result = $ticket->updateTicket($id, $data);
		break;

	case 'updateRating':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : "";
		$rate = isset($_POST['Rate']) ? $_POST['Rate'] : "";
		$result = $ticket->updateRating($id, $rate);
		break;

	case 'updateReassignment':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : "";
		$value = isset($_POST['Value']) ? $_POST['Value'] : "";
		$result = $ticket->updateReassignment($id, $value);
		break;

	case 'updateTicketStatus':
		$id = isset($_REQUEST['Ticket_Id']) ? $_REQUEST['Ticket_Id'] : "";
        $status = isset($_REQUEST['Status']) ? $_REQUEST['Status'] : '';
        $solution = isset($_REQUEST['Solution']) ? $_REQUEST['Solution'] : '';
		$result = $ticket->updateTicketStatus($id, $status, $solution);
		break;

	case 'reassignUserTicket':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : "";
        $supportedBy = isset($_POST['Supported_By']) ? $_POST['Supported_By'] : '';
        $category = isset($_POST['Category_Id']) ? $_POST['Category_Id'] : '';
        $level = isset($_POST['Level']) ? $_POST['Level'] : '';
		$result = $ticket->reassignUserTicket($id, $supportedBy, $category, $level);
		break;

	case 'deleteTicket':
		$id = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$ticket->deleteTicket($id);
		break;

	case 'insertComment':
		$ticketid = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$comment = isset($_POST['Comment']) ? $_POST['Comment'] : die;
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$ticket->insertComment($ticketid, $comment, $token);
		break;

	case 'isTicketIssued': 
		$ticketid = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$result = $ticket->isTicketIssued($ticketid, $token);
		break;

	case 'isTicketSupported': 
		$ticketid = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$result = $ticket->isTicketSupported($ticketid, $token);
		break;

	case 'updateReassignmentReason': 
		$ticketid = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$reason = isset($_POST['Reason']) ? $_POST['Reason'] : die;
		$ticket->updateReassignmentReason($ticketid, $reason);
		break;

	case 'reopeningOfTicket': 
		$ticketid = isset($_POST['Ticket_Id']) ? $_POST['Ticket_Id'] : die;
		$reason = isset($_POST['Reason']) ? $_POST['Reason'] : die;
		$ticket->reopeningOfTicket($ticketid, $reason);
		break;

	case 'getIssuedSupportedRecentTickets': 
		$token = isset($_POST['Token']) ? $_POST['Token'] : die;
		$result = $ticket->getIssuedSupportedRecentTickets($token);
		break;

    default:
		$result = "No value";
}

echo json_encode($result);

