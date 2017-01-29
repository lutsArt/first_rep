<?php

namespace Site;

class Database {
	public $connection;

	function __construct() {
		$servername = parse_ini_file(ROOT_PATH.'/conf/conf.ini')['server_name'];
		$username	= parse_ini_file(ROOT_PATH.'/conf/conf.ini')['user_name'];
		$password 	= parse_ini_file(ROOT_PATH.'/conf/conf.ini')['password'];
		$db 		= parse_ini_file(ROOT_PATH.'/conf/conf.ini')['db'];

		$this->connection = mysqli_connect(
			$servername,
			$username,
			$password,
			$db) or
		die('Database Connection Error: '.mysqli_connect_error());
	}

	public function close_database() {
		return mysqli_close($this->connection);
	}
	public function query($query) {
		$query = mysqli_query($this->connection ,$query) or die('Query Execution Error: '.mysqli_error($this->connection));
		return $query;
	}
	public function fetch_assoc($query) {
		$query = mysqli_fetch_assoc($query);
		return $query;
	}
}