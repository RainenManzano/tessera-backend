<?php
include '../../config/Database.php';

class SupportPreference{

	public function getSupports() {
		$sql = "SELECT u.User_Id, u.Lastname, u.Firstname, u.Middlename, '' AS Scope
					FROM users u
					INNER JOIN activation on activation.User_Id = u.User_Id
					where (u.Role = 1 OR u.Role = 2) AND (activation.Is_Activated=1)
					ORDER BY u.User_Id DESC";
		$supports = (new Database())->query($sql, [], 'select');
		for($ctr=0; $ctr<count($supports); $ctr++) {
			$categories = $this->getPreferences($supports[$ctr]["User_Id"]);
			for($counter=0; $counter<count($categories); $counter++) {
				if($supports[$ctr]["Scope"]=="") 
					$supports[$ctr]["Scope"] = $categories[$counter]["Name"];
				else 
					$supports[$ctr]["Scope"] = $supports[$ctr]["Scope"].", ".$categories[$counter]["Name"];
			}
		}
		return $supports;
	}

	public function getPreferences($id)
	{
		$sql = " SELECT s.*, u.Lastname, u.Firstname, u.Middlename, c.Name, c.Description
					from users u 
					INNER JOIN support_preferences s on s.Support_Id = u.User_Id
					INNER JOIN categories c on c.Category_Id = s.Category_Id
					WHERE u.User_Id = $id;";
		$res = (new Database())->query($sql, [$id],'select');
		return $res;
	}

	public function getPreferenceBySupportAndCategory($data) {
		$sql = " SELECT * from  support_preferences 
					WHERE Support_Id = ? AND 
					Category_Id = ?;";
		$res = (new Database())->query($sql, [ $data["Support_Id"], $data["Category_Id"] ],'select');
		return $res;
	}

	public function insertPreference($data)
	{
		$sql = "INSERT INTO support_preferences(Support_Id, Category_Id) VALUE(?, ? )";

		$res = (new Database())->query(
			$sql,
			[$data['Support_Id'],$data['Category_Id']],
			'insert'
		);
		return $res;
    }

    public function deletePreference($id)
    {
        $sql = " DELETE FROM support_preferences WHERE Id = $id";
        $res = (new Database())->query($sql, [$id],'delete');
        return  $res;
    }



}