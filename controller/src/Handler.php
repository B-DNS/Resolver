<?php
	namespace App\Controller;
	//класс для связывания Logic, Database и User
	class Handler {
		public $logic      = null; //Logic obj
		public $user       = null; //User obj
		public $renderT    = null; //Render obj
		public $last_error = "";   //string
		
		private $db      = null; //DataBase obj
		private $enviro  = null; //Environment obj
		private $mfcoin  = null; //MFCoin obj
		
		public function __construct() {
			$this->enviro  = new \App\Model\Environment();
			//$this->db      = new \App\Model\DataBase();
			$this->logic   = new \App\Controller\Logic();
			//$this->user    = new \App\Controller\User();
			$this->renderT = new \App\Controller\Render([]);
			
			//$this->logic->setdb($this->db);
			//$this->user->setdb($this->db);
			//$this->logic->setUser($this->user);
		}
		
		public function mfcoin_init() {
			$this->mfcoin = new \App\Model\MFCoin();
			$this->logic->set_mfcoin($this->mfcoin);
		}
		
		public function render($data = []) {
			$this->renderT = new \App\Controller\Render($data);
			$this->renderT->twigRender();
		}
	}
