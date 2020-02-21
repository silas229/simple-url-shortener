<?php
require '../vendor/autoload.php';

use App\DB\Connection;
use App\DB\Tables;
use App\User;

session_start();

$pdo = (new Connection())->connect();
$tables = new Tables($pdo);
if (!$tables->check_setup_permission()) header("Location: ./?reason=setup_blocked");

$errors = [];
if ($_GET['create'] == 1) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	$user = new User($pdo);
	try {
		$user_id = $user->new($email, $password, $password2, 'admin');

		$_SESSION['user'] = $user->get($user_id);
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}

	if (empty($errors)) header("Location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Setup Simple URL Shortener</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
	<section class="hero is-light">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">Setup <strong>Simple URL Shortener</strong></h1>
			</div>
		</div>
	</section>
	<section class="section"><div class="container">
		<?php
		if (!empty($errors)) {
			foreach ($errors as $error) {
				echo '<div class="notification is-danger">
                <button class="delete"></button>' .
								$error .
							'</div>';
			}
		}
		?>
		<form action="?create=1" method="post">
			<fieldset class="box">
				<legend class="label subtitle is-4 has-text-centered">Create user account</legend>
				<div class="field">
					<label for="email" aria-label="required">e-mail address: <strong><abbr title="required">*</abbr></strong></label>
					<div class="control has-icons-left has-icons-right">
						<input class="input" type="email" id="email" name="email" autocomplete="email" placeholder="Type in your e-mail address!" required>
						<span class="icon is-small is-left">
							<i class="fas fa-envelope"></i>
						</span>
					</div>
					<p class="help"></p>
				</div>
				<div class="field">
					<label for="password" aria-label="required">Password: <strong><abbr title="required">*</abbr></strong></label>
					<div class="control has-icons-left has-icons-right">
						<input class="input" type="password" id="password" name="password" placeholder="Choose a strong password!" required>
						<span class="icon is-small is-left">
							<i class="fas fa-key"></i>
						</span>
					</div>
					<p class="help"></p>
				</div>
				<div class="field">
					<label for="password2" aria-label="required">Retype password: <strong><abbr title="required">*</abbr></strong></label>
					<div class="control has-icons-left has-icons-right">
						<input class="input" type="password" id="password2" name="password2" placeholder="Type in your password again!" required>
						<span class="icon is-small is-left">
							<i class="fas fa-key"></i>
						</span>
					</div>
					<p class="help"></p>
				</div>
				<div class="field is-grouped">
					<div class="control">
						<input class="button is-link" type="submit" value="Create user">
					</div>
					<div class="control">
						<input type="reset" class="button is-link is-light" value="Clear input">
					</div>
					<p class="help"></p>
				</div>
			</fieldset>
		</form>
	</div></section>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<script src="js/setup.js"></script>
	</body>
</html>
