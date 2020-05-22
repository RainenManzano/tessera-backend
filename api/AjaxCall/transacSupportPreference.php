<?php

include '../Classes/SupportPreference.php';

$action = $_GET['action'];
$result = [];

switch($action) {

	case 'getSupports':
		$pref = new SupportPreference();	
		$result = $pref->getSupports();
		echo json_encode($result);
  		break;

	case 'getPreferences':
		$id = isset($_POST['Support_Id']) ? $_POST['Support_Id'] : die;
		$pref = new SupportPreference();
		$result = $pref->getPreferences($id);
		echo json_encode($result);
  		break;

  	case 'insertPreference':
		$data =[
			'Category_Id' => isset($_REQUEST['Category_Id']) ? $_REQUEST['Category_Id'] : "",
			'Support_Id' => isset($_REQUEST['Support_Id']) ? $_REQUEST['Support_Id'] :''	
		 ];
		$pref = new SupportPreference();
		$valid = $pref->getPreferenceBySupportAndCategory($data);
		if(count($valid)==0) {
			$result = $pref->insertPreference($data);
			echo json_encode($result);
		}
		break;

	case 'deletePreference':
			$id = isset($_POST['Id']) ? $_POST['Id'] : die;
			$pref = new SupportPreference();
			$result = $pref->deletePreference($id);
			echo json_encode($result);
			break;
		
}

