<?php

include '../Classes/Department.php';

$action = $_GET['action'];
$result = [];

switch($action) {

	case 'getDepartment':
			$depart = new Department();
			$result = $depart->getDepartment();
			echo json_encode($result);
  		break;

	case 'getSingleDepartment':
		  $depart = new Department();
		  $id = isset($_POST['Department_Id']) ? $_POST['Department_Id'] : die;
		  $result = $depart->getSingleDepartment($id);
		  echo json_encode($result);
		  break;

	case 'updateDepartment':
			$id = isset($_POST['Department_Id']) ? $_POST['Department_Id'] : die;
			$data =[
		 			'Name' => isset($_POST['Name']) ? $_POST['Name'] : '',
		 			'Description' => isset($_POST['Description']) ? $_POST['Description'] : ''
				 ];

			$depart = new Department();
			$result = $depart->updateDepartment($id, $data);
			echo json_encode($result);
			break;

	case 'addDepartment':
			$data =[
		 			'Name' => isset($_POST['Name']) ? $_POST['Name'] : "",
		 			'Description' => isset($_POST['Description']) ? $_POST['Description'] :''	
				 ];

			$depart = new Department();
			$result = $depart->addDepartment($data);
			echo json_encode($result);
			break;

	case 'deleteDepartment':
			$id = isset($_POST['Department_Id']) ? $_POST['Department_Id'] : die;
			$depart = new Department();
			$result = $depart->deleteDepartment($id);
			echo json_encode($result);
			break;

	case 'doesDepartmentExists':
		$department = isset($_POST['Department']) ? $_POST['Department'] : '';
		$depart = new Department();
		$result = $depart->doesDepartmentExists($department);
		echo json_encode($result);
  		break;

    default:
			$result = "No value";
}

