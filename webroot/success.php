<?php

	session_start();

	require_once(dirname(dirname(__FILE__)) . '/classes/config.php');

	$Config = new Config();

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
			<p>Congratulations, you are now connected to the internet!</p>
		</div>
	</body>
</html>