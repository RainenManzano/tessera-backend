<?php
include '../../config/Database.php';

class Department{


    //getting Department

    public function getDepartment()
    {
        $sql = " SELECT * FROM departments order by Department_Id DESC";
		$departQuery = (new Database())->query($sql);
		return $departQuery;
    }

	public function getSingleDepartment($id)
	{
		$sql = " SELECT * FROM departments WHERE Department_Id = $id";
		$departQuery = (new Database())->query($sql, [$id],'select');

		return $departQuery;
	}


    public function updateDepartment($id, $data)
	{
		$sql = "UPDATE `departments` SET Name = ?, Description = ? WHERE Department_Id = ?";

		$departQuery = (new Database())->query(
			$sql,
			[$data['Name'], $data['Description'], $id],
			'update'
		);

		return $departQuery;
	}


    public function addDepartment($data)
	{
		$sql = "INSERT INTO departments(Name,Description) VALUE(?, ? )";

		$departQuery = (new Database())->query(
			$sql,
			[$data['Name'],$data['Description']],
			'insert'
		);

		return $departQuery;
    }

    public function deleteDepartment($id)
    {
        $sql = " DELETE FROM departments WHERE Department_Id = $id";
        $departQuery = (new Database())->query($sql, [$id],'delete');

        return  $departQuery;
    }

    public function doesDepartmentExists($department) {
		$result = [];
		$sql = "SELECT * FROM departments where Name = ?";
		$res = (new Database())->query($sql, [$department], 'select');
		if(count($res) > 0) {	
			return true;
		} else {
			return false;
		}
	}


}