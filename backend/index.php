<?php

use App\DB\Connection;
use App\URLs;

require '../vendor/autoload.php';

session_start();

$pdo = (new Connection())->connect();

var_dump($_SESSION);
$user = $_SESSION['user'];
$errors = [];

if ($_GET['reason'] === 'setup-blocked') $error[] = 'You are not allowed to do that.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Simple URL Shortener</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
	<link rel="stylesheet" href="style.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
	<div class="navbar-brand">
		<a class="navbar-item" href="https://bulma.io">Simple URL Shortener</a>

		<a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
		</a>
	</div>
	<div class="navbar-menu">
		<div class="navbar-end">
			<?php if (isset($user) && !empty($user)): ?>
				<a class="navbar-item" href="setting.php">
					<figure class="image is-24x24">
						<img class="is-rounded" src="https://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( $user['email']) ) ); ?>?s=24&d=mp" alt="👤">
					</figure>
					&nbsp;Settings
				</a>
			<?php endif; ?>
		</div>
	</div>
</nav>
<section class="hero is-light">
	<div class="hero-body">
		<div class="container">
			<h1 class="title">Simple URL Shortener</h1>
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
</div></section>
<section class="section"><div class="container">
	<nav class="panel is-primary">
		<p class="panel-heading">New Shortlink</p>
		<div class="panel-block">
			<form action="../api/v1/create" method="post" class="w100">
				<fieldset>
					<div class="field">
						<p class="control">
							<input type="url" class="input" placeholder="Enter URL" required>
						</p>
					</div>
					<div class="field has-addons">
						<p class="control w100">
							<input type="text" class="input" placeholder="Custom alias (optional)">
						</p>
						<p class="control">
							<button class="button is-primary" type="submit">
								<span class="icon is-small"><i class="fas fa-plus" aria-hidden="true"></i></span>
								<span>Create</span>
							</button>
						</p>
					</div>
				</fieldset>
			</form>
		</div>
	</nav>
</div></section>
<section class="section"><div class="container">
	<nav class="panel is-primary">
		<p class="panel-heading">Shortened Links</p>
		<div class="panel-block">
			<p class="control has-icons-left">
				<input class="input" type="search" placeholder="Search">
				<span class="icon is-left"><i class="fas fa-search" aria-hidden="true"></i></span>
			</p>
		</div>
		<?php
		$urls = (new URLs($pdo))->get_list();
		foreach ($urls as $url): ?>
			<div class="panel-block">
				<a class="panel-icon has-text-link" href="<?php echo $url['url']; ?>" target="_blank" title="Open <?php echo $url['url']; ?>"><i class="fas fa-link" aria-hidden="true"></i></a>
				<?php echo parse_url($url['url'])['host'];
				echo ' &ndash;&nbsp;'; ?>
				<a href="../<?php echo $url['code']; ?>" target="_blank" title="Open <?php echo $url['url']; ?>"><?php echo $url['code']; ?></a>
				<?php if(!empty($url['comment'])) echo "&nbsp;<span class='is-italic has-text-grey'>{$url['comment']}</span>"; ?>
				<div class="field is-grouped are-small box-right">
					<?php /*
					<p class="control">
						<a class="button is-light is-success" href="<?php echo $url['url']; ?>" target="_blank" title="Open <?php echo $url['url']; ?>" id="edit-<?php echo $url['url']; ?>"><span class="icon is-small"><i class="fas fa-eye" aria-hidden="true"></i></span></a>
					</p>
          */ ?>
					<p class="control">
						<button class="button is-light is-warning" title="Edit" id="edit-<?php echo $url['code']; ?>"><span class="icon is-small"><i class="fas fa-edit" aria-hidden="true"></i></span></button>
					</p>
					<p class="control">
						<button class="button is-light is-info" title="View stats" id="stats-<?php echo $url['code']; ?>"><span class="icon is-small"><i class="fas fa-chart-area" aria-hidden="true"></i></span></button>
					</p>
					<p class="control">
						<button class="button is-light is-danger" title="Delete" id="delete-<?php echo $url['code']; ?>"><span class="icon is-small"><i class="fas fa-trash" aria-hidden="true"></i></span></button>
					</p>
					</div>
			</div>
		<?php endforeach;	?>
	</nav>
	</div></section>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="js/index.js"></script>
</body>
</html>

