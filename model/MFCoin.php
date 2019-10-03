<?php
	namespace App\Model;
	
	class MFCoin {
		public $last_error       = "";   //string
		private $coin_connection = null; //CoinRPC obj
		
		public function __construct() {
			$this->coin_connection = new \App\Model\CoinRPC(
				getenv('rpc_user'),
				getenv('rpc_pass'),
				getenv('rpc_host'),
				getenv('rpc_port')
			);
			if($this->coin_connection->error != "") {
				$this->last_error = "can't connect to mfcoin wallet";
			}
		}
		
		public function check_status(): bool {
			if($this->last_error != "" && $coin_connection != "") {
				return true;
			} else {
				return false;
			}
		}
		
		public function getBalanceFix($account = "fund"): float {
			$balance_fund = (float) $this->coin_connection->getbalance($account);
			$balance_fix  = (float) $this->coin_connection->getbalance("");
			return $balance_fund + $balance_fix;
		}
		
		public function NVS_nameExists($entry_name = "entry") {
			return $this->coin_connection->name_show($entry_name);
		}
	}
	