<?php
include '../../libs/Query.php';
class Database
{
	// specify your own database credentials
	private $host = "localhost";
	private $database = "support_ticket";
	private $username = "root";
	private $password = "";
	public $conn;

	// get the database connection
	public function connection() {
		$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
		} catch(PDOException $ex){
			echo "Connection error: " . $ex->getMessage();
		}

		return $this->conn;
	}

	/**
	 * query function
	 * for database tests_usersdb
	 *
	 * @param string $sql
	 * @param array $data
	 * @param string $function  'select', 'insert', 'update', 'delete', 'execute'
	 * @return array
	 */
	public function query($sql, $data = [], $function = 'select') {
		return (new Query($this->connection(), $sql))->{$function}($data);
	}

}

