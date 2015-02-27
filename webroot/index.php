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
	</head>
	<body>
		<?php if($Config->Has('general', 'logo')): ?><img src="<?=$Config->Get('general', 'logo') ?>" /><?php endif; ?>
		<h1><?=($Config->Has('general', 'company') ? $Config->Get('general', 'company') : 'UniFi') ?></h1>
		<form action="auth.php" method="post">
			<input type="hidden" name="redirect" value="<?=(isset($_GET['url']) ? $_GET['url'] : ($Config->Has('general', 'website') ? $Config->Get('general', 'website') : 'http://www.ubnt.com')) ?>" />
			<input type="hidden" name="mac" value="<?=(isset($_GET['mac']) ? $_GET['mac'] : '') ?>" />
			<input type="checkbox" name="accept" value="1" id="input-accept" required /> <label for="input-accept">I solemnly swear I'm up to no good</label>
			<button type="submit">Lumos</button>
		</form>
	</body>
</html>