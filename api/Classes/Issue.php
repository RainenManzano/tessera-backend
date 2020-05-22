<?php
include '../../config/Database.php';

class Issue
{

    public function getIssue()
    {
        $sql = "SELECT i.*, p.*, c.Name, c.Description as CategoryDescription
        			FROM issues i
        			INNER JOIN categories c on c.Category_Id = i.Category_Id
        			INNER JOIN priorities p on p.Priority_Id = i.Priority_Id
        			order by i.Issue_Id DESC ";
		$res = (new Database())->query($sql);
		return $res;
    }

    public function getTopIssues()
    {
        $sql = "SELECT i.Issue, COUNT(i.Issue_Id) as 'total'
					from tickets t
					inner join issues i on i.Issue_Id = t.Issue
					GROUP BY i.Issue_Id
					ORDER BY total DESC";
		$res = (new Database())->query($sql);
		return $res;
    }

	public function getSingleIssue($id)
	{
		$sql = "SELECT i.*, p.*, c.Name, c.Description as CategoryDescription
        			FROM issues i
        			INNER JOIN categories c on c.Category_Id = i.Category_Id
        			INNER JOIN priorities p on p.Priority_Id = i.Priority_Id
        			WHERE Issue_Id = ?";
		$res = (new Database())->query($sql, [$id],'select');
		return $res;
    }

    public function updateIssue($id, $data)
	{
		$sql = "UPDATE `issues` SET Issue = ?, Description = ? ,  Category_Id = ?, Priority_Id = ?
				WHERE Issue_Id = ?";
		$res = (new Database())->query(
			$sql, [
				$data['Issue'], 
				$data['Description'], 
				$data['Category_Id'], 
				$data['Priority_Id'], 
				$id
			],'update'
		);
		return $res;
	}


    public function addIssue($data)
	{
		$sql = "INSERT INTO issues(Issue, Description, Category_Id, Priority_Id) VALUES(?, ?, ?, ?)";

		$res = (new Database())->query(
			$sql, [
				$data['Issue'], 
				$data['Description'],
				$data['Category_Id'],
				$data['Priority_Id']
			], 'insert'
		);
		return $res;
    }

    public function deleteIssue($id)
    {
        $sql = " DELETE FROM issues WHERE Issue_Id = $id";
        $res = (new Database())->query($sql, [$id],'delete');
        return  $res;
    }

}