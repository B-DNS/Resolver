<?php
	namespace App\Controller;
	
	class User {
		public $data = [
			"uid"        => 0,
			"nick_name"  => "Anonymous",
			"is_auth"    => false,
			"hash"       => "",
			"email"      => "",
			"address"    => "unknown"
		];
		public $last_error = "";
		
		protected $db = null;
		
		private $client = null;
		private $config = [];
		
		public function __construct(bool $need_checkAuth = false) {
			$this->data['is_auth']   = isset($_SESSION['uid']);
			if($this->data['is_auth']) {
				$this->data['uid']       = $_SESSION['uid'];
				/* $this->data['nick_name'] = $_SESSION['nick_name'];
				$this->data['email']     = $_SESSION['email'];
				$this->data['hash']      = md5($_SESSION['email']); */
			}
			
			if($need_checkAuth) {
				$this->checkAuth();
			}
		}
		
		public function checkAuth(): void {
			if($this->data['is_auth'] == false) {
				header("Location: /"); exit;
			}
		}
		
		public function setdb($db): void {
			$this->db = &$db;
		}
	}
