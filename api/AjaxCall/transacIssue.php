<?php

include '../Classes/Issue.php';

$issue = new Issue();
$action = $_GET['action'];
$result = [];

switch($action) {


	case 'getIssue':
		$result = $issue->getIssue();
		echo json_encode($result);
  		break;

    case 'getSingleIssue':
		$id = isset($_POST['Issue_Id']) ? $_POST['Issue_Id'] : "";
		$result = $issue->getSingleIssue($id);
		echo json_encode($result);
        break;


	case 'updateIssue':
			$id = isset($_POST['Issue_Id']) ? $_POST['Issue_Id'] : "";
			$data =[
		 		'Issue' => isset($_POST['Issue']) ? $_POST['Issue'] : '',
		 		'Description' => isset($_POST['Description']) ? $_POST['Description'] : '',
                'Category_Id' => isset($_POST['Category_Id']) ? $_POST['Category_Id'] : '',
                'Priority_Id' => isset($_POST['Priority_Id']) ? $_POST['Priority_Id'] : ''
			];
			$result = $issue->updateIssue($id, $data);
			echo json_encode($result);
			break;

	case 'addIssue':
			$data =[
		 		'Issue' => isset($_POST['Issue']) ? $_POST['Issue'] : '',
		 		'Description' => isset($_POST['Description']) ? $_POST['Description'] : '',
                'Category_Id' => isset($_POST['Category_Id']) ? $_POST['Category_Id'] : '',
                'Priority_Id' => isset($_POST['Priority_Id']) ? $_POST['Priority_Id'] : ''
			];
			$result = $issue->addIssue($data);
			echo json_encode($result);
			break;

	case 'deleteIssue':
			$id = isset($_POST['Issue_Id']) ? $_POST['Issue_Id'] : "";
			$result = $issue->deleteIssue($id);
			echo json_encode($result);
			break;

	case 'getTopIssues':
			$result = $issue->getTopIssues();
			echo json_encode($result);
			break;

    default:
			$result = "No value";
}

