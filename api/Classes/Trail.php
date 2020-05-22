<?php
require_once('../../config/Database.php');

class Trail
{

    public function getTrailsById($ticket_id)
    {
        $sql = "SELECT * FROM trails
        			WHERE Ticket_Id = ?
        			order by Date_Created DESC ";
		$res = (new Database())->query($sql, [$ticket_id]);
		return $res;
    }

    public function insertTrail($user_id, $ticket_id, $type)
	{
        $name = $this->getName($user_id);
        $msg = "";
        if($type=="add") {
            $msg = $name." has added the ticket";
        } else if($type=="reassignmentapproved") {
            $msg = $name." request has been approved";
        } else if($type=="reassignment") {
            $msg = "Ticket has been transferred to ".$name;
        }
		$sql = "INSERT INTO trails(Ticket_Id, Trail_Log) VALUES(?, ?)";
		$res = (new Database())->query($sql, [$ticket_id, $msg], 'insert');
		return $res;
    }

    public function getNameViaUpdate($ticket_id, $type, $status) {
        $name = "";
        $msg = "";
        if($type=="support") {
            $sql = "SELECT u.* FROM users u
                    INNER JOIN tickets t on t.SupportedBy = u.User_Id
                    WHERE t.Ticket_Id = ?";
            $res = (new Database())->query($sql, [$ticket_id], 'select');
            foreach($res as $result) {
                $name = $result["Firstname"]." ".$result["Lastname"];
            }   
        } else if($type=="issuer") {
            $sql = "SELECT u.* FROM users u
                    INNER JOIN tickets t on t.CreatedBy = u.User_Id
                    WHERE t.Ticket_Id = ?";
            $res = (new Database())->query($sql, [$ticket_id]);
            foreach($res as $result) {
                $name = $result["Firstname"]." ".$result["Lastname"];
            }   
        }
        $msg = $name." has changed the status into ".$status;
        $sql = "INSERT INTO trails(Ticket_Id, Trail_Log) VALUES(?, ?)";
        $res = (new Database())->query($sql, [$ticket_id, $msg], 'insert');
        return $res;
    }

    public function getName($user_id) {
        $name = "";
        $sql = "SELECT * FROM users
                    WHERE User_Id = ?";
        $res = (new Database())->query($sql, [$user_id]);
        foreach($res as $result) {
            $name = $result["Firstname"]." ".$result["Lastname"];
        }   
        return $name;
    }








}