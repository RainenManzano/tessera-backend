<?php
	include '../../config/Database.php';
	require_once("Jwt_key.php");

	class Message {
		private $jwt;

		public function __construct() {
			$this->jwt = new JWT_Key();
		}

		public function getConversationsList($token){
	 		$tokenArray = $this->jwt->decodeToken($token);
	 		$user_id = $tokenArray->id;
	 		$sql = " SELECT m.From_User, m.To_User
						FROM messages m
						INNER JOIN users u on u.User_Id = m.To_User
						INNER JOIN positions p on p.Position_Id = u.Position_Id
						WHERE m.From_User = ? OR m.To_User = ?
						ORDER BY m.Message_Id DESC ";
			$query = (new Database())->query($sql, [$user_id, $user_id]);
			$result = $this->filterConversationsList($user_id, $query);
			return $result;
		}

		public function filterConversationsList($user_id, $result) {
			$newArray = [];
			$listArray;
			$ctr = 0;
			foreach($result as $conversation) {
				$newArray[] = $conversation["From_User"];
				$newArray[] = $conversation["To_User"];
			}
			$idArray = array_unique($newArray);
			foreach($idArray as $id) {
				if($id!=$user_id) {
					$listArray[] = $this->getUserInfo($id);
				}
			}
			return $listArray;
		}

		public function getUserInfo($id) {
			$array = [];
			$sql = " SELECT u.User_Id, u.Firstname, u.Lastname, u.Img_Name, p.Position_Name
						FROM users u
						INNER JOIN positions p on p.Position_Id = u.Position_Id
						WHERE u.User_Id = ?";
			$query = (new Database())->query($sql, [$id]);
			foreach($query as $res) {
				$array["User_Id"] = $res["User_Id"];
				$array["Firstname"] = $res["Firstname"];
				$array["Lastname"] = $res["Lastname"];
				$array["Img_Name"] = $res["Img_Name"];
				$array["Position_Name"] = $res["Position_Name"];
			}
			return $array;
		}

		public function getMessages($token, $recipient_id){
	 		$tokenArray = $this->jwt->decodeToken($token);
	 		$user_id = $tokenArray->id;
	 		$sql = " SELECT '1' AS Is_User, Message, Date_Created
						FROM messages
						WHERE From_User = ?
						AND To_User = ?
						UNION ALL
						SELECT  '0' AS Is_User, Message, Date_Created
						FROM messages
						WHERE From_User = ?
						AND To_User = ?
						ORDER BY Date_Created DESC";
			$query = (new Database())->query($sql, [$user_id, $recipient_id, $recipient_id, $user_id]);
			return $query;
		}

		public function insertMessage($token, $to_user, $message){
	 		$tokenArray = $this->jwt->decodeToken($token);
	 		$from_user = $tokenArray->id;
	 		$sql = " INSERT INTO messages(From_User, To_User, Message) VALUES(?, ?, ?);";
			$query = (new Database())->query($sql, [$from_user, $to_user, $message]);
			return $query;
		}









	}
