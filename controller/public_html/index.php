<?php
	session_start();
	require_once __DIR__ . "/../vendor/autoload.php";
	
	$handler = new \App\Controller\Handler();
	
	$handler->render([
		'tag'   => 'home',
		'title' => 'Blank project',
		'user'  => $handler->user->data
	]);
	