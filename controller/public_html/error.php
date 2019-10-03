<?php
	session_start();
	require_once __DIR__ . "/../vendor/autoload.php";
	
	$handler = new \App\Controller\Handler();
	$err_code = \App\Model\Utilities::checkINT($_GET['code']);
	
	$handler->render([
		'tag'   => 'error',
		'title' => 'Ошибка',
		'code'  => $err_code,
		'user'  => $handler->user->data
	]);
	