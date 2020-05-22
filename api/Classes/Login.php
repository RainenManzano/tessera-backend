<?php
require_once("User.php");
require_once("Jwt_key.php");

class Login extends User
{

    private $jwt;

    public function __construct() {
        $this->jwt = new JWT_Key();
    }

    public function checkLogin($username, $password)
    {
        $database = new Database();
        $db = $database->connection();
        $sql = "SELECT u.User_Id, u.Role, a.Is_Activated
                from users u 
                INNER JOIN activation a on u.User_Id = a.User_Id
                where u.Username = :username AND
                u.Pwd = :password;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':username' => $username, ':password' => $password));
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $token = array(
                "id" => $row['User_Id'],
                "role" => $row["Role"]
            );
            $data = array(
                "message" => "",
                "token" => $this->jwt->encodeToken($token),
                "userId" => $row['User_Id'],
                "isActivated" => $row["Is_Activated"]
            );
        }
        else {
            $data = array(
                "message" => "Invalid Username or Password!",
            );
        }
        return $data;
    }

    public function verifyCode($user_id, $Verification_Code)
    {
        $sql = "UPDATE activation 
                SET Is_Activated = 1 
                WHERE Verification_Code = ? AND 
                User_Id = ?";
        $res = (new Database())->query($sql, [$Verification_Code, $user_id],'update');
        if ($res['affected_rows'] != 0){
            $data = array(
                'message' => true
            );
        } else {
            $data = array(
                'message' => false
            );
        }
        return $data;
    }

    public function resendCode($user_id) {
        $database = new Database();
        $db = $database->connection();
        $sql = "SELECT a.Verification_Code, u.Company_Email
                from activation a
                INNER JOIN users u on u.User_Id = a.User_Id
                where a.User_Id = :userId";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':userId' => $user_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $subject = "Verification Number";
        $body = 'This is verification '.$row["Verification_Code"];
        $this->sendVerificationCode($row["Company_Email"], $subject, $body);
    }

    public function getRole($token) {
        $tokenArray = $this->jwt->decodeToken($token);
        return $tokenArray->role;
    }

}
