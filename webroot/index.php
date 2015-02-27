<?php

	session_start();

	require_once(dirname(dirname(__FILE__)) . '/classes/config.php');

	$Config = new Config();

	if(!isset($_SESSION['mac']) && isset($_GET['id'])) { // Grab MAC-access
		$_SESSION['mac'] = $_GET['id'];
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title><?=($Config->Has('general', 'company') ? $Config->Get('general', 'company') : 'UniFi') ?> Guest Portal</title>
		<link rel="stylesheet" type="text/css" href="/style.css" />
	</head>
	<body>
		<div id="main">
			<?php if($Config->Has('general', 'logo')): ?><img class="logo" src="<?=$Config->Get('general', 'logo') ?>" alt="<?=($Config->Has('general', 'company') ? $Config->Get('general', 'company') : 'UniFi') ?>" /><?php else: ?><h1><?=($Config->Has('general', 'company') ? $Config->Get('general', 'company') : 'UniFi') ?></h1><?php endif; ?>
			<form action="/auth.php" method="post">
				<input type="hidden" name="redirect" value="<?=(isset($_GET['url']) ? $_GET['url'] : ($Config->Has('general', 'website') ? $Config->Get('general', 'website') : 'http://www.ubnt.com')) ?>" />
				<input type="hidden" name="mac" value="<?=(isset($_GET['mac']) ? $_GET['mac'] : '') ?>" />
				<p>
					<input type="checkbox" name="accept" value="1" id="input-accept" required /> <label for="input-accept"><?= ($Config->Has('general', 'accepttext') ? $Config->Get('general', 'accepttext') : 'I promise not to do anything stupid or illegal while using this awesome, freely provided service. Thanks ' . ($Config->Has('general', 'company') ? $Config->Get('general', 'company') : 'UniFi') . ', you\'re the best!') ?></label>
				</p>
				<p style="text-align: center;">
					<button class="button large blue" type="submit">Connect</button>
				</p>
			</form>
		</div>
	</body>
</html>