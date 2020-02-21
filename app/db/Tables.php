<?php


namespace App\DB;


use App\Config;
use App\User;

/**
 * Class Tables
 * functions and operations for managing the database tables
 * @author  Silas_229 <contact@silas229.de>
 * @package App\DB
 */
class Tables {
	/**
	 * PDO object
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Tables constructor.
	 * Initialize the object with a specified PDO object
	 *
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * create
	 * Create tables in the database
	 */
	public function create() {
		$commands = [
			'CREATE TABLE IF NOT EXISTS sus_log
				(
				    code       INT
				        CONSTRAINT table_name_sus_urls_code_fk
				            REFERENCES sus_log (code)
				            ON UPDATE CASCADE ON DELETE SET NULL,
				    referrer   TEXT,
				    user_agent TEXT,
				    time       TIMESTAMP DEFAULT current_timestamp
				);',
			'CREATE TABLE IF NOT EXISTS sus_users
				(
				    email      TEXT NOT NULL,
				    password   TEXT NOT NULL,
				    role       TEXT DEFAULT user,
				    created_at TIMESTAMP DEFAULT current_timestamp,
				    updated_at TIMESTAMP DEFAULT current_timestamp
				);',
			'CREATE UNIQUE INDEX IF NOT EXISTS sus_users_email_uindex
    ON sus_users (email);',
			'CREATE TABLE IF NOT EXISTS sus_urls
				(
				    code      VARCHAR(200) DEFAULT (substr(lower(hex(randomblob(32))),1,6)) NOT NULL
				        PRIMARY KEY,
				    url       TEXT NOT NULL,
				    user_id   INT  NOT NULL
				        CONSTRAINT sus_users_sus_users_id_fk
				            REFERENCES sus_users
				            ON UPDATE CASCADE ON DELETE SET NULL,
				    comment   TEXT,
				    timestamp DATETIME     DEFAULT current_timestamp,
				    clicks    INT          DEFAULT 0 NOT NULL
				);'
		];

		// execute sql $commands
		foreach ($commands as $command) {
			$this->pdo->exec($command);
		}
	}

	/**
	 * getTableList
	 * @return array list of tables
	 */
	public function getTableList() {
		$stmt = $this->pdo->query("SELECT name
                               FROM sqlite_master
                               WHERE type = 'table'
                               ORDER BY name");
		$tables = [];
		while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			$tables[] = $row['name'];
		}

		return $tables;
	}

	/**
	 * check_setup_permission
	 * checks if setup is allowed
	 *
	 * @uses \App\User
	 *
	 * @return bool permission
	 */
	public function check_setup_permission() {
		if ($this->getTableList() !== Config::TABLE_LIST) $this->create();

		return ((new User($this->pdo))->get_amount() === 0);
	}
}
