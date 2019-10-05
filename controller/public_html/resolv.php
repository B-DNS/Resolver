<?php
	session_start();
	require_once __DIR__ . "/../vendor/autoload.php";
	use \App\Model\Utilities as Utils;
	
	$handler = new \App\Controller\Handler();
	$domain = Utils::data_filter($_GET['domain']);
	
	//максимум IPшников в выдаче
	$query_maxIPs = Utils::data_filter($_GET['n']);
	if($query_maxIPs == 'all' || empty($query_maxIPs)) {
		//-1 => все
		$query_maxIPs = -1;
	} else {
		$query_maxIPs += 0;
		if(!($query_maxIPs > 0) || !is_int($query_maxIPs)) {
			$query_maxIPs = -1;
		}
	}
	
	//надо ли перемешать выдачу "случайно"
	$query_shuffleIPs = Utils::data_filter($_GET['r']);
	switch($query_shuffleIPs) {
		default:
			$query_shuffleIPs = false;
			break;
		case '1':
			$query_shuffleIPs = true;
			break;
	}
	
	if($domain == "") {
		$handler->logic->domain_error();
	}
	
	$handler->mfcoin_init();
	
	$domain_data = $handler->logic->get_domain($domain, $query_maxIPs, $query_shuffleIPs);
	if($domain_data == []) {
		//некорректная bdns-запись или произошла неведомая ошибка
		$handler->logic->domain_error();
	}
	echo $domain_data['ip_list'];
	