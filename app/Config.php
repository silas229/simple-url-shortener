<?php


namespace App;


/**
 * Class Config
 * Config file
 * @author  Silas_229 <contact@silas229.de>
 * @package App
 */
class Config {
	/**
	 * Path to the SQLite database
	 */
	const SQLITE_FILE_PATH = __DIR__ . '/../db/database.sqlite3';
	const TABLE_LIST = ['sus_log', 'sus_urls', 'sus_users'];
}
