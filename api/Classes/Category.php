<?php
include '../../config/Database.php';

class Category
{
    // `Category_Id`, `Name`, `Description`
    public function getCategory()
    {
        $sql = " SELECT * FROM categories order by Category_Id DESC ";
		$catQuery = (new Database())->query($sql);
		return $catQuery;
    }


	public function getSingleCategory($id)
	{
		$sql = " SELECT * FROM categories WHERE Category_Id = $id";
		$catQuery = (new Database())->query($sql, [$id],'select');

		return $catQuery;
    }


    public function updateCategory($id, $data)
	{

		$sql = "UPDATE `categories` SET Name = ?, Description = ?  WHERE Category_Id = ?";

		$catQuery = (new Database())->query(
			$sql,
			[$data['Name'], $data['Description'], $id],
			'update'
		);

		return $catQuery;
	}


    public function addCategory($data)
	{

		$sql = "INSERT INTO categories(Name, Description) VALUE(?, ?)";

		$catQuery = (new Database())->query(
			$sql,
			[$data['Name'], $data['Description']],
			'insert'
		);

		return $catQuery;
    }

    public function deleteCategory($id)
    {
        $sql = " DELETE FROM categories WHERE Category_Id = $id";
        $catQuery = (new Database())->query($sql, [$id],'delete');

        return  $catQuery;
    }

    public function doesCategoryExists($category) {
		$result = [];
		$sql = "SELECT * FROM categories where Name = ?";
		$res = (new Database())->query($sql, [$category], 'select');
		if(count($res) > 0) {	
			return true;
		} else {
			return false;
		}
	}



}