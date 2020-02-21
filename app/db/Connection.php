<?php


namespace App\DB;


use App\Config;

/**
 * Class Connection
 * Connection to the SQLite database
 * @author Silas_229 <contact@silas229.de>
 * @package App\DB
 */
class Connection {
	/**
	 * PDO instance
	 * @var $pdo \PDO
	 */
	private $pdo;

	/**
	 * connect
	 * Initialize the object with a specified PDO object
	 *
	 * @return \PDO
	 */
	public function connect() {
		if ($this->pdo == null) {
			try {
				$this->pdo = new \PDO("sqlite:" . Config::SQLITE_FILE_PATH);
			} catch (\PDOException $e) {
				echo "Connection error: " . $e;
			}
		}
		return $this->pdo;
	}

	/**
	 * exists
	 * return true if db file already exists
	 *
	 * @return bool
	 */
	public function exists() {
		return file_exists(Config::SQLITE_FILE_PATH);
	}
}
