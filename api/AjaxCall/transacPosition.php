<?php

include '../Classes/Position.php';

$action = isset($_GET['action']) ? $_GET['action'] : die;
$result = [];

    // `Position_Id`, `Position_Name`, `Position_Desc`

switch($action) {


	case 'getPosition':
			$position = new Position();
			$result = $position->getPosition();
			echo json_encode($result);
  		break;


    case 'getSinglePosition':
		  $position = new Position();
		  $id = isset($_POST['Position_Id']) ? $_POST['Position_Id'] : die;
		  $result = $position->getSinglePosition($id);
		  echo json_encode($result);
          break;


	case 'updatePosition':
			$id = isset($_POST['Position_Id']) ? $_POST['Position_Id'] : die;
			$data =[
		 			'Position_Name' => isset($_POST['Position_Name']) ? $_POST['Position_Name'] : '',
                    'Position_Desc' => isset($_POST['Position_Desc']) ? $_POST['Position_Desc'] : '',
                    'Department' => isset($_POST['Department']) ? $_POST['Department'] : ''
				 ];

			$position = new Position();
			$result = $position->updatePosition($id, $data);
			echo json_encode($result);
			break;

	case 'addPosition':
			$data =[
		 			 'Position_Name' => isset($_POST['Position_Name']) ? $_POST['Position_Name'] : die,
                     'Position_Desc' => isset($_POST['Position_Desc']) ? $_POST['Position_Desc'] : die,
                     'Department' => isset($_POST['Department']) ? $_POST['Department'] : die
				 ];

			$position = new Position();
			$result = $position->addPosition($data);
			echo json_encode($result);
			break;

	case 'deletePosition':
			$id = isset($_POST['Position_Id']) ? $_POST['Position_Id'] : die;
			$position = new Position();
			$result = $position->deletePosition($id);
			echo json_encode($result);
			break;


	case 'doesPositionExists':
		$positionName = isset($_POST['Position']) ? $_POST['Position'] : '';
		$position = new Position();
		$result = $position->doesPositionExists($positionName);
		echo json_encode($result);
  		break;

    default:
			$result = "No value";
}

