<?php

include '../Classes/User.php';

$action = isset($_GET['action']) ? $_GET['action'] : die;

$result = [];
$users = new User();
switch($action) {

	case 'getUsers':
			$result = $users->getUsers();
			echo json_encode($result);
  			break;

  	case 'getAllUsersActive':
			$result = $users->getAllUsersActive();
			echo json_encode($result);
  			break;

  	case 'getUsersCount':
			$result = $users->getUsersCount();
			echo json_encode($result);
  			break;

  	case 'getSupportsCount':
			$result = $users->getSupportsCount();
			echo json_encode($result);
  			break;

  	case 'getSingleUserById':
			$id = isset($_REQUEST['User_Id']) ? $_REQUEST['User_Id'] : '';
			$result = $users->getSingleUserById($id);
			echo json_encode($result);
			break;

	case 'getSingleUserByToken':
			$token = isset($_REQUEST['Token']) ? $_REQUEST['Token'] : '';
			$result = $users->getSingleUserByToken($token);
			echo json_encode($result);
			break;

	case 'getLoggedInUser':
			$token = isset($_REQUEST['Token']) ? $_REQUEST['Token'] : '';
			$result = $users->getLoggedInUser($token);
			echo json_encode($result);
			break;

	case 'getUsersSupport':
		$result = $users->getUsersSupport();
		echo json_encode($result);
  		break;

	case 'addUser':
			$data =[
					'Employee_Id' => isset($_POST['Employee_Id']) ? $_POST['Employee_Id'] : '',
					'Lastname' => isset($_POST['Lastname']) ? $_POST['Lastname'] : '',
					'Firstname' => isset($_POST['Firstname']) ? $_POST['Firstname'] : '',
					'Middlename' => isset($_POST['Middlename']) ? $_POST['Middlename'] : '',
					'Department_Id' => isset($_POST['Department_Id']) ? $_POST['Department_Id'] : '',
					'Position_Id' => isset($_POST['Position_Id']) ? $_POST['Position_Id'] : '',
					'Username' => isset($_POST['Username']) ? $_POST['Username'] : '',
					'Pwd' => isset($_POST['Pwd']) ? $_POST['Pwd'] : '',
					'Role' => isset($_POST['Role']) ? $_POST['Role'] : '',
					'Company_Email' => isset($_POST['Company_Email']) ? $_POST['Company_Email'] : ''
			];
			$userId = $users->addUser($data);
			if($_FILES) {
				$file = $_FILES["File"];
				$timeStamp = $_POST["TimeStamp"];
				$users->updateImage($userId["insert_id"], $file, $timeStamp);
			}
			$users->insertActivation($data["Company_Email"], $userId["insert_id"]);
			break;


	case 'deleteSingleUser':
		$id = isset($_POST['User_Id']) ? $_POST['User_Id'] : die;
		$result = $users->deleteSingleUser($id);
		echo json_encode($users);
		break;


	case 'updateUser':
		$id = isset($_POST['User_Id']) ? $_POST['User_Id'] : '';
		$status = isset($_POST['Status']) ? $_POST['Status'] : '';
		if($status=='true' || $status==1) 
			$status = 1;
		else 
			$status = 0;
		$data =[
			'Employee_Id' => isset($_POST['Employee_Id']) ? $_POST['Employee_Id'] : '',
			'Company_Email' => isset($_POST['Email']) ? $_POST['Email'] : '',
			'Lastname' => isset($_POST['Lastname']) ? $_POST['Lastname'] : '',
			'Firstname' => isset($_POST['Firstname']) ? $_POST['Firstname'] : '',
			'Middlename' => isset($_POST['Middlename']) ? $_POST['Middlename'] : '',
			'Department_Id' => isset($_POST['Department_Id']) ? $_POST['Department_Id'] : '',
			'Position_Id' => isset($_POST['Position_Id']) ? $_POST['Position_Id'] : '',
			'Username' => isset($_POST['Username']) ? $_POST['Username'] : '',
			'Role' => isset($_POST['Role']) ? $_POST['Role'] : ''
		];
		$result = $users->updateUser($id, $data);
		$users->updateStatus($id, $status);
		echo json_encode($result);
		break;

	case 'updateUserBio':
		$token = isset($_POST['Token']) ? $_POST['Token'] : '';
		$emp_id = isset($_POST['Employee_Id']) ? $_POST['Employee_Id'] : '';
		$email = isset($_POST['Email']) ? $_POST['Email'] : '';
		$firstname = isset($_POST['Firstname']) ? $_POST['Firstname'] : '';
		$middlename = isset($_POST['Middlename']) ? $_POST['Middlename'] : '';
		$lastname = isset($_POST['Lastname']) ? $_POST['Lastname'] : '';
		$users->updateUserBio($token, $emp_id, $email, $firstname, $middlename, $lastname);
		break;

	case 'updateUserAccount':
		$token = isset($_POST['Token']) ? $_POST['Token'] : '';
		$username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$new_password = isset($_POST['New_Password']) ? $_POST['New_Password'] : '';
		$users->updateUserAccount($token, $username, $new_password);
		break;

	case 'updateEmail':
		$token = isset($_POST['Token']) ? $_POST['Token'] : '';
		$email = isset($_POST['Email']) ? $_POST['Email'] : '';
		$res = $users->updateEmail($token, $email);
		echo json_encode($res);
		break;

	case "changeProfilePicture":
		$token = (isset($_POST["Token"])) ? $_POST["Token"]: "";
		$timeStamp = (isset($_POST["TimeStamp"])) ? $_POST["TimeStamp"]: "";
		$file = (isset($_FILES["File"])) ? $_FILES["File"]: "";
		$id = $users->deleteImage($token);
		$filename = $users->updateImage($id, $file, $timeStamp);
		echo json_encode($filename);
		break;

	case 'doesEmployeeIdExists':
		$employeeId = isset($_POST['Employee_Id']) ? $_POST['Employee_Id'] : '';
		$result = $users->doesEmployeeIdExists($employeeId);
		echo json_encode($result);
  		break;

  	case 'doesEmailExists':
		$email = isset($_POST['Email']) ? $_POST['Email'] : '';
		$result = $users->doesEmailExists($email);
		echo json_encode($result);
  		break;

  	case 'doesUsernameExists':
		$username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$result = $users->doesUsernameExists($username);
		echo json_encode($result);
  		break;

  	case 'passwordSame':
		$pwd = isset($_POST['Password']) ? $_POST['Password'] : '';
		$result = $users->passwordSame($pwd);
		echo json_encode($result);
  		break;

    default:
			$result = "No value";
}
