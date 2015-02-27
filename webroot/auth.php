<?php

	require_once(dirname(dirname(__FILE__)) . '/classes/config.php');

	$Config = new Config();

	if(isset($_POST['accept']) && (isset($_SESSION['mac']) || isset($_POST['mac']))) {
		$mac = (isset($_SESSION['mac']) ? $_SESSION['mac'] : $_POST['mac']);

		require_once(dirname(dirname(__FILE__)) . '/classes/unifi.php');

		$Controller = new UniFi($Config->Get('controller', 'host'), $Config->Get('controller', 'user'), $Config->Get('controller', 'password'), ($Config->Has('controller', 'port') ? $Config->Get('controller', 'port') : 8443), ($Config->Has('controller', 'protocol') ? $Config->Get('controller', 'protocol') : 'https'));

		try {
			$Controller->Authorize($mac);
		} catch(Exception $e) {
			error_log(get_class($e) . ': ' . $e->getMessage());
			header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/index.php?mac=' . $mac));
		}

		//Set no caching
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { // AJAX-request
			header('Content-type: application/json; charset=utf-8', true, 200);
			die();
		} else {
			if(isset($_POST['redirect'])) {
				sleep(5);
				header('Location: ' . $_POST['redirect']);
			} else {
				header('Location: ' . '/success.php?mac=' . $mac . '&redirect=' . $_POST['redirect']);
			}
			die();
		}
	}

	if(isset($_SERVER['HTTP_REFERER'])) {
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	} else {
		header('Location: /index.php');
	}

?>