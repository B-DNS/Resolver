<?php
	namespace App\Model;
	class Environment {
		function __construct() {
			$this->loadFromENV();
		}
		
		function loadFromENV() {
			$dotenv = \Dotenv\Dotenv::create(__DIR__ . "/../");
			$dotenv->load();
		}
	}
	