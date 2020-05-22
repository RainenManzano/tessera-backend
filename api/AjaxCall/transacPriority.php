<?php

include '../Classes/Priority.php';

$action = $_GET['action'];
$result = [];

        // `Priority_Id`, `Level`, `Days`, `Hours`, `Minutes`
switch($action) {

	case 'getPriority':
		$priority = new Priority();
		$result = $priority->getPriority();
		echo json_encode($result);
  		break;

	case 'updatePriority':
		$id = isset($_POST['Priority_Id']) ? $_POST['Priority_Id'] : die;
		$data =[
			'Level' => isset($_POST['Level']) ? $_POST['Level'] : '',
	 		'Days' => isset($_POST['Days']) ? $_POST['Days'] : '',
           	'Hours' => isset($_POST['Hours']) ? $_POST['Hours'] : '',
           	'Minutes' => isset($_POST['Minutes']) ? $_POST['Minutes'] : '',
           	'Label' => isset($_POST['Label']) ? $_POST['Label'] : ''
		 ];
		$priority = new Priority();
		$result = $priority->updatePriority($id, $data);
		echo json_encode($result);
		break;

	case 'addPriority':
		$data =[
	 		'Level' => isset($_POST['Level']) ? $_POST['Level'] : die,
	 		'Days' => isset($_POST['Days']) ? $_POST['Days'] : die,
            'Hours' => isset($_POST['Hours']) ? $_POST['Hours'] : die,
            'Minutes' => isset($_POST['Minutes']) ? $_POST['Minutes'] : die,
            'Label' => isset($_POST['Label']) ? $_POST['Label'] : die
		 ];
		$priority = new Priority();
		$result = $priority->addPriority($data);
		echo json_encode($result);
		break;

	case 'deletePriority':
		$id = isset($_POST['Priority_Id']) ? $_POST['Priority_Id'] : die;
		$priority = new Priority();
		$result = $priority->deletePriority($id);
		echo json_encode($result);
		break;


	case 'getSinglePriority':
		$priority = new Priority();
		$id = isset($_POST['Priority_Id']) ? $_POST['Priority_Id'] : die;
		$result = $priority->getSinglePriority($id);
		echo json_encode($result);
		break;

	case 'doesLevelExists':
		$priority = new Priority();
		$level = isset($_POST['Level']) ? $_POST['Level'] : '';
		$id = isset($_POST['Id']) ? $_POST['Id'] : '';
		$result = $priority->doesLevelExists($level, $id);
		echo json_encode($result);
  		break;

  	case 'doesLabelExists':
		$priority = new Priority();
		$label = isset($_POST['Label']) ? $_POST['Label'] : '';
		$id = isset($_POST['Id']) ? $_POST['Id'] : '';
		$result = $priority->doesLabelExists($label, $id);
		echo json_encode($result);
  		break;

    default:
			$result = "No value";
}

