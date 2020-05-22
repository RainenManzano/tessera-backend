<?php
include '../../config/Database.php';
include 'Mailer.php';
require_once("Jwt_key.php");

class User {

	private $jwt;
	private $location;

	public function __construct() {
		$this->jwt = new JWT_Key();
		$this->location = "./../../assets/images/profile_pictures/";
	}

	public function getUsers()
	{
		$sql = " SELECT u.User_Id, u.Employee_Id, u.Lastname, u.Firstname, u.Middlename, u.Company_Email, u.Role,
					a.Name, c.Position_Name, u.Username
					FROM users u
					INNER JOIN departments a ON u.Department_Id = a.Department_Id
					INNER JOIN positions c ON u.Position_Id = c.Position_Id
					order by u.User_Id desc";
		$usersQuery = (new Database())->query($sql);
		return $usersQuery;
	}

	public function getAllUsersActive()
	{
		$sql = " SELECT u.User_Id, u.Employee_Id, u.Lastname, u.Firstname, u.Middlename, u.Company_Email, u.Role, u.Img_Name,
					a.Name, c.Position_Name, u.Username
					FROM users u
					INNER JOIN departments a ON u.Department_Id = a.Department_Id
					INNER JOIN positions c ON u.Position_Id = c.Position_Id
					INNER JOIN activation act ON act.User_Id = u.User_Id
					WHERE act.Is_Activated = 1 
					order by u.User_Id desc";
		$usersQuery = (new Database())->query($sql);
		return $usersQuery;
	}

	public function getUsersCount()
	{
		$sql = "SELECT count(User_Id) as UserCount from users;";
		$usersQuery = (new Database())->query($sql);
		return $usersQuery;
	}

	public function getSupportsCount()
	{
		$sql = "SELECT count(User_Id) as SupportCount 
					from users
					WHERE Role = 1 OR Role = 2;";
		$usersQuery = (new Database())->query($sql);
		return $usersQuery;
	}

	public function getSingleUserById($id)
	{
		$sql = "SELECT u.User_Id, u.Employee_Id, u.Lastname, u.Firstname, u.Middlename, u.Company_Email, u.Username, u.Role, 
					u.Img_Name, a.Department_Id, a.Name,c.Position_Id, c.Position_Name, act.Is_Activated AS Status
					FROM users u
					INNER JOIN activation act on u.User_Id = act.User_Id
					INNER JOIN departments a ON u.Department_Id = a.Department_Id
					INNER JOIN positions c ON u.Position_Id = c.Position_Id
					where u.User_Id = $id";
		$usersQuery = (new Database())->query($sql, [$id],'select');
		return $usersQuery;
	}

	public function getSingleUserByToken($token)
	{
		$tokenArray = $this->jwt->decodeToken($token);
		$id = $tokenArray->id;
		$sql = "SELECT u.User_Id, u.Employee_Id, u.Lastname, u.Firstname, u.Middlename, u.Company_Email, a.Department_Id, a.Name,c.Position_Id, c.Position_Name, u.Username, u.Role, u.Img_Name
			FROM users u
			INNER JOIN departments a ON u.Department_Id = a.Department_Id
			INNER JOIN positions c ON u.Position_Id = c.Position_Id
			where u.User_Id = ?";
		$usersQuery = (new Database())->query($sql, [$id],'select');
		return $usersQuery;
	}

	public function getLoggedInUser($token)
	{
		$tokenArray = $this->jwt->decodeToken($token);
		$id = $tokenArray->id;
		$sql = "SELECT u.User_Id, u.Employee_Id, u.Lastname, u.Firstname, u.Middlename, 
								u.Company_Email, u.Username, u.Role, u.Img_Name,
				 				a.Department_Id, a.Name,c.Position_Id, c.Position_Name
					FROM users u
					INNER JOIN departments a ON u.Department_Id = a.Department_Id
					INNER JOIN positions c ON u.Position_Id = c.Position_Id
					where u.User_Id = $id";
		$usersQuery = (new Database())->query($sql, [$id],'select');
		return $usersQuery;
	}

	public function getUsersSupport() {
		$sql = "SELECT User_Id, Lastname, Firstname, Middlename
					FROM users
					where Role = 1
					OR Role = 2
					ORDER BY User_Id DESC";
		$supports = (new Database())->query($sql, [], 'select');
		return $supports;
	}

	public function deleteSingleUser($id)
	{
		$sql = " DELETE FROM users WHERE User_Id = $id";
		$usersQuery = (new Database())->query($sql, [$id],'delete');

		return $usersQuery;

	}

	public function addUser($data)
	{
		$sql = " INSERT INTO users(Employee_Id , Lastname, Firstname, Middlename, Department_Id, Position_Id, Username, Pwd, Company_Email, Role) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ? ,?)";
		$usersQuery = (new Database())->query($sql,
		[ $data['Employee_Id'], $data['Lastname'] , $data['Firstname'] ,$data['Middlename'], $data['Department_Id'], $data['Position_Id'], $data['Username'], $data['Pwd'], $data['Company_Email'], $data['Role'] ], 'insert');
		return $usersQuery;

	}

	public function updateUser($id, $data)
	{
		$sql = " UPDATE `users` 
					SET Employee_Id = ?, Company_Email = ?, Lastname= ?, Firstname= ?, Middlename= ?,
					Department_Id= ?, Position_Id= ?, Username= ?, Role = ? 
					WHERE User_Id = ? ";
		$usersQuery = (new Database())->query(
			$sql,
			[ $data['Employee_Id'], $data['Company_Email'], $data['Lastname'] , $data['Firstname'] ,$data['Middlename'], $data['Department_Id'], $data['Position_Id'], $data['Username'], $data['Role'], $id ],
			'update'
		);
		return $usersQuery;
	}

	public function updateStatus($id, $status)
	{
		$sql = " UPDATE activation 
					SET Is_Activated = ?
					WHERE User_Id = ? ";
		$usersQuery = (new Database())->query(
			$sql,
			[ $status, $id ],
			'update'
		);
		return $usersQuery;
	}

	public function updateUserBio($token, $emp_id, $email, $firstname, $middlename, $lastname)
	{
		$tokenArray = $this->jwt->decodeToken($token);
		$user_id = $tokenArray->id;
		$sql = " UPDATE `users` 
					SET Employee_Id = ?, Company_Email = ?, Lastname= ?, Firstname= ?, Middlename= ?
					WHERE User_Id = ? ";
		$usersQuery = (new Database())->query(
			$sql,
			[$emp_id, $email, $lastname, $firstname, $middlename, $user_id],
			'update'
		);
		return $usersQuery;
	}

	public function updateUserAccount($token, $username, $new_password)
	{
		$tokenArray = $this->jwt->decodeToken($token);
		$user_id = $tokenArray->id;
		$array = [];
		if($new_password=="") {
			$sql = " UPDATE `users` 
						SET Username = ?
						WHERE User_Id = ? ";
			$array = [$username, $user_id];
		} else {
			$sql = " UPDATE `users` 
						SET Username = ?, Pwd = ?
						WHERE User_Id = ? ";
			$array = [$username, $new_password, $user_id];
		}
		$usersQuery = (new Database())->query(
			$sql,
			$array,
			'update'
		);
		return $usersQuery;
	}

	public function updateEmail($token, $email)
	{
		$tokenArray = $this->jwt->decodeToken($token);
		$user_id = $tokenArray->id;
		$sql = " UPDATE `users` 
					SET Company_Email = ?
					WHERE User_Id = ? ";
		$usersQuery = (new Database())->query(
			$sql,
			[$email, $user_id],
			'update'
		);
		return $usersQuery;
	}

	public function generateCode()
	{
		$code = "";
		for($a=0;$a<10;$a++ )
		{
			$code = $code.''.rand(0, 9);

		}
		return $code;
	}

	public function insertActivation($email, $user_id){
		$gen = $this->generateCode();
		$subject = "Verification Number";
		$body = 'This is verification '.$gen;
		$sql = "INSERT INTO activation (Verification_Code, Is_Activated, User_Id) VALUES (?, 0, ?)";
		$query = (new Database())->query($sql,[
				$gen,
				$user_id
		], 'insert');

		$this->sendVerificationCode($email, $subject, $body);
		return $query;
	}

	public function sendVerificationCode($email, $subject, $body) {
		$mail = new Mailer();
	    $mail->sendMail($email, $subject, $body);
	}

	public function updateImage($id, $file, $timeStamp) {
		$array = explode('.', $file["name"]);
		$filename = $array[0].$timeStamp.".".$array[1];
		$sql = "UPDATE users
				SET Img_Name = ?
				WHERE User_Id = ? ";
		$usersQuery = (new Database())->query(
			$sql,
			[ $filename, $id ],
			'update'
		);
		move_uploaded_file($file["tmp_name"], $this->location.$filename);
		return $filename;
	}

	public function deleteImage($token) {
		$user = $this->getSingleUserByToken($token);
		$img_name = ($user[0]["Img_Name"]!=null)? $user[0]["Img_Name"]: 'missing.jpg';
		$id = $user[0]["User_Id"];
		if(file_exists($this->location.$img_name)) {
			unlink($this->location.$img_name);
		}
		return $id;
	}

	public function doesEmployeeIdExists($employeeId) {
		$result = [];
		$sql = "SELECT * FROM users where Employee_Id = ?";
		$usersQuery = (new Database())->query($sql, [$employeeId], 'select');
		if(count($usersQuery) > 0) {	
			return true;
		} else {
			return false;
		}
	}

	public function doesEmailExists($email) {
		$result = [];
		$sql = "SELECT * FROM users where Company_Email = ?";
		$usersQuery = (new Database())->query($sql, [$email], 'select');
		if(count($usersQuery) > 0) {	
			return true;
		} else {
			return false;
		}
	}

	public function doesUsernameExists($username) {
		$result = [];
		$sql = "SELECT * FROM users where Username = ?";
		$usersQuery = (new Database())->query($sql, [$username], 'select');
		if(count($usersQuery) > 0) {	
			return true;
		} else {
			return false;
		}
	}

	public function passwordSame($pwd) {
		$result = [];
		$sql = "SELECT * FROM users where Pwd = ?";
		$usersQuery = (new Database())->query($sql, [$pwd], 'select');
		if(count($usersQuery) > 0) {	
			return true;
		} else {
			return false;
		}
	}


}
