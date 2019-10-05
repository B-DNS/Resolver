<?php
	session_start();
	require_once __DIR__ . "/../vendor/autoload.php";
	use \App\Model\Utilities as Utils;
	
	$handler = new \App\Controller\Handler();
	$domain = Utils::data_filter($_GET['domain']);
	if($domain == "") {
		$handler->logic->domain_error();
	}
	
	$handler->mfcoin_init();
	
	$domain_exists = $handler->logic->isDomainExists($domain);
	if($domain_exists) {
		exit("xx");
	} else {
		$handler->logic->domain_error();
	}
	