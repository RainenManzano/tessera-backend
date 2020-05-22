<?php
require_once('../../config/Database.php');
require_once('Jwt_key.php');

class Notification
{

    public function getNotifications($token)
    {
    	$jwt = new JWT_Key();
    	$tokenArray = $jwt->decodeToken($token);
    	$user_id = $tokenArray->id;
        $sql = "SELECT *
        			FROM notifications
        			WHERE User_To_Notify = ?
        			order by Notification_Id DESC ";
		$res = (new Database())->query($sql, [$user_id]);
		return $res;
    }


    public function insertNotification($data)
	{
		$sql = "INSERT INTO notifications(User_To_Notify, Notification_Message, Url_String, Query_Params) VALUES(?, ?, ?, ?)";
		$res = (new Database())->query(
			$sql, $data, 'insert'
		);
		return $res;
    }

    public function readNotification($id)
    {
        $sql = "UPDATE notifications SET Checked = 1 WHERE Notification_Id = ?;";
        $res = (new Database())->query(
            $sql, [$id], 'update'
        );
        return $res;
    }

     public function readAllNotifications($idArray)
    {
         $sql = "UPDATE notifications SET Checked = 1 WHERE Notification_Id = ?;";
        foreach($idArray as $id) {
            $res = (new Database())->query($sql, [$id], 'update');
        }
        return $res;
    }

    public function getToBeNotifiedId($ticketId, $message, $type) {
    	if($type=="issuer") {
    		$sql = "SELECT CreatedBy from tickets
    					WHERE Ticket_Id = ".$ticketId;
			$res = (new Database())->query($sql);
            $url = "/sts/tickets/".$ticketId;
			$data = [ $res[0]['CreatedBy'], $message, $url, "view-issued-tickets" ];
			$this->insertNotification($data);
    	} else if($type=="support") {
    		$sql = "SELECT SupportedBy from tickets
    					WHERE Ticket_Id = ".$ticketId;
			$res = (new Database())->query($sql);
            $url = "/sts/tickets/".$ticketId;
			$data = [ $res[0]['SupportedBy'], $message, $url, "view-supported-tickets" ];
			$this->insertNotification($data);
    	} else if($type=="head") {
            $sql = "SELECT u.Firstname, u.Lastname 
                        from tickets t
                        INNER JOIN users u ON u.User_Id = t.SupportedBy
                        WHERE t.Ticket_Id = ".$ticketId;
            $res = (new Database())->query($sql);
            $url = "/sts/tickets/".$ticketId;
            foreach($res as $row)
                $message = $row["Firstname"]." ".$row["Lastname"]." has requested for reassignment";
            $sql = "SELECT User_Id from users
                        WHERE Role = 0 OR Role = 1";
            $result = (new Database())->query($sql);
            foreach($result as $head) {
                $data = [ $head['User_Id'], $message, $url, "view-all-tickets" ];
                $this->insertNotification($data);
            }  
        }
    }








}