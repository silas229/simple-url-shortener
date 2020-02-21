<?php


namespace App;

use Exception;

/**
 * Class User
 * functions and operations for sus_log table
 * @author  Silas_229 <contact@silas229.de>
 * @package App
 */
class User {
	/**
	 * @var \PDO
	 */
	private $pdo;

	public function __construct(\PDO $pdo) {
		$this->pdo = $pdo;
	}

	public function new(string $email, string $password, string $password2, string $role = 'user') {
		$email = trim($email);
		$password = trim($password);
		$password2 = trim($password2);

		if (!$this->validate_input($email, $password, $password2)) return false;

		$password = password_hash($password, PASSWORD_BCRYPT);

		$sql = "INSERT INTO sus_users(email, password, role) VALUES(:email, :password, :role)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':email'    => $email,
			':password' => $password,
			':role'     => $role
		]);
		$stmt->fetch(\PDO::FETCH_ASSOC);

		return $this->pdo->lastInsertId();
	}

	private function validate_input($email, $password, $password2) {
		if (empty($email) || empty($password) || empty($password2)) throw new Exception("All fields must be filled in!");
		if ($password !== $password2) throw new Exception("The passwords are not equal!");
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("The e-mail address is not valid");
		if ($this->check_email($email)) throw new Exception("This e-mail address is not available");

		return true;
	}

	public function get(int $id) {
		$sql = "SELECT rowid, email, role FROM sus_users WHERE rowid = :id LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':id' => $id
		]);

		$user = $stmt->fetch(\PDO::FETCH_ASSOC);
		return [
			'id'    => $user['rowid'],
			'email' => $user['email'],
			'role'  => $user['role']
		];
	}

	public function get_amount() {
		$sql = "SELECT rowid FROM sus_users";
		$stmt = $this->pdo->query($sql);
		$stmt->fetch();

		return $stmt->rowCount();
	}

	public function check_email(string $email) {
		$sql = "SELECT rowid FROM sus_users WHERE email = :email LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':email' => $email
		]);

		return ($stmt->rowCount() > 0);
	}
}
