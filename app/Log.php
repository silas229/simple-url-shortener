<?php


namespace App;

/**
 * Class Log
 * functions and operations for sus_log table
 * @author  Silas_229 <contact@silas229.de>
 * @package App\DB
 */
class Log {
	/**
	 * PDO object
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Log constructor.
	 * Initialize the object with a specified PDO object
	 *
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * insert_click
	 * Inserts the click into the log table and increases the clicks count by 1
	 *
	 * @param string $code
	 * @param string $referrer
	 * @param string $useragent
	 *
	 * @uses \App\URLs
	 *
	 * @return string
	 */
	public function insert_click(string $code, string $referrer = null, string $useragent = null) {
		$sql = "INSERT INTO sus_log('code', 'referrer', 'user_agent') VALUES(:code, :referrer, :user_agent)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':code' => $code,
			':referrer' => $referrer,
			':user_agent' => $useragent
		]);

		(new URLs($this->pdo))->increase_clicks($code);

		return $this->pdo->lastInsertId();
	}
}
