<?php
include '../../config/Database.php';

class Position
{

    public function getPosition()
    {
        $sql = "SELECT p.*, d.Name as Department
        		from positions p
        		LEFT JOIN departments d on p.Department_Id = d.Department_Id
				order by p.Position_Id DESC;";
		$positionQuery = (new Database())->query($sql);
		return $positionQuery;
    }


	public function getSinglePosition($id)
	{
		$sql = "SELECT p.*, d.Name as Department
				from positions p
				LEFT JOIN departments d on d.Department_Id = p.Department_Id
				WHERE p.Position_Id = $id";
		$positionQuery = (new Database())->query($sql, [$id],'select');

		return $positionQuery;
    }


    public function updatePosition($id, $data)
	{

		$sql = "UPDATE `positions` SET Position_Name = ?, Position_Desc = ?, Department_Id = ? 
				WHERE Position_Id = ?";

		$positionQuery = (new Database())->query(
			$sql,
			[$data['Position_Name'], $data['Position_Desc'], $data["Department"], $id],
			'update'
		);

		return $positionQuery;
	}


    public function addPosition($data)
	{

		$sql = "INSERT INTO positions(Position_Name, Position_Desc, Department_Id) VALUE(?, ?, ?)";
		$positionQuery = (new Database())->query(
			$sql,
			[ $data['Position_Name'], $data['Position_Desc'], $data["Department"] ],
			'insert'
		);

		return $positionQuery;
    }

    public function deletePosition($id)
    {
        $sql = " DELETE FROM positions WHERE Position_Id = $id";
        $positionQuery = (new Database())->query($sql, [$id],'delete');

        return  $positionQuery;
    }

    public function doesPositionExists($position) {
		$result = [];
		$sql = "SELECT * FROM positions where Position_Name = ?";
		$res = (new Database())->query($sql, [$position], 'select');
		if(count($res) > 0) {	
			return true;
		} else {
			return false;
		}
	}

}