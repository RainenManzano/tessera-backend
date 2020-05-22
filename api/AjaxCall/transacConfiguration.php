<?php

include '../Classes/Configuration.php';


$action = isset($_GET['action']) ? $_GET['action'] : die;

$result = [];

switch($action) {

	case 'getConfigurations':
			$config = new Configuration();
			$result = $config->getConfigurations();
			echo json_encode($result);
  		break;

	case 'getSingleConfiguration':

			$configuration = new Configuration();
			$id = isset($_POST['Id']) ? $_POST['Id'] : '';
			$result = $configuration->getSingleConfigurations($id);
			echo json_encode($result);
			break;

	case 'getSingleConfigurationByName':

			$configuration = new Configuration();
			$name = isset($_POST['Event_Name']) ? $_POST['Event_Name'] : '';
			$result = $configuration->getSingleConfigurationByName($name);
			echo json_encode($result);
			break;

	case 'addConfiguration':
			$data =[
					'Event_Name' => isset($_POST['Event_Name']) ? $_POST['Event_Name'] : '',
					'Value' => isset($_POST['Value']) ? $_POST['Value'] : ''
			];
			$config = new Configuration();
			$result = $config->getSingleConfigurationByName($data["Event_Name"]);
			if(count($result)>0) {
				$config->updateConfigurationsByName($data);
			} else {
				$config->addConfigurations($data);
			}
			break;


	case 'deleteSingleConfiguration':
			$id = isset($_POST['Id']) ? $_POST['Id'] : die;
			$config = new Configuration();
			$result = $config->deleteSingleConfigurations($id);
			echo json_encode($result);
			break;


	case 'updateConfiguration':
			$id = isset($_POST['Id']) ? $_POST['Id'] : '';

			$data =[
				'Event_Name' => isset($_POST['Event_Name']) ? $_POST['Event_Name'] : '',
				'Value' => isset($_POST['Value']) ? $_POST['Value'] : ''
			];
			$config = new Configuration();
			$result = $config->updateConfigurations($id, $data);
			echo json_encode($result);
			break;

	case 'backupDatabase':
		$config = new Configuration();
		$config->backupDatabase();
		break;

    default:
			$result = "No value";
}
