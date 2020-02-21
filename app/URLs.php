<?php


namespace App;


/**
 * Class URLs
 * functions and operations for sus_urls table
 * @author  Silas_229 <contact@silas229.de>
 * @package App
 */
class URLs {
	/**
	 * PDO object
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * URLs constructor.
	 * Initialize the object with a specified PDO object
	 *
	 * @param \PDO $pdo
	 */
	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * get_single
	 * Get a single url; used for quick url redirection
	 *
	 * @param string $code
	 *
	 * @return string URL
	 */
	public function get_single(string $code) {
		$sql = "SELECT url FROM sus_urls WHERE code = :code";
		$stmt = $this->pdo->prepare($sql);
		$item = $stmt->execute([
			':code' => $code
		]);
		$item = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $item['url'];
	}

	/**
	 * get_list
	 * Returns a list of all created short urls
	 *
	 * @return array
	 */
	public function get_list() {
		$stmt = $this->pdo->query("SELECT * FROM sus_urls");
		$items = [];
		while ($item = $stmt->fetch(\PDO::FETCH_ASSOC))
			$items = $item;

		return $items;
	}

	public function insert(string $url, string $code = null, string $comment = null) {
		$user_id = $_SESSION['id'];

		$sql = "INSERT INTO sus_urls(code, url, user_id, comment) VALUES(:code, :url, :user_id, :comment)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':code' => $code,
			':url' => $url,
			':user_id' => $user_id,
			':comment' => $comment
		]);

		return $this->pdo->lastInsertId();
	}

	/**
	 * increase_clicks
	 * Increase a click for a short url by 1
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function increase_clicks(string $code) {
		$sql = "UPDATE sus_urls SET clicks = clicks + 1 WHERE code = :code";
		$stmt = $this->pdo->prepare($sql);
		$item = $stmt->execute([
			':code' => $code
		]);

		return $item;
	}
}
