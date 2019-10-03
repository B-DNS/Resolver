<?php
	namespace App\Controller;
	
	class Logic {
		private $db     = null; //DataBase obj
		private $mfcoin = null; //MFCoin obj
		
		public function __construct() {
			//
		}
		
		public function setdb($db = null) {
			$this->db = &$db;
		}
		
		public function setUser($user = null): void {
			$this->user = &$user;
		}
		
		public function set_mfcoin($mfcoin_obj = null): void {
			$this->mfcoin = &$mfcoin_obj;
		}
		
		public function getDomainInfo($domain = "sagleft.mfcoin"): array {
			$entry_name = "dns:" . $domain;
			$result = $this->mfcoin->NVS_nameExists($entry_name);
			//exit(json_encode($result));
			//var_dump(is_bool($result)); exit;
			if(is_bool($result)) {
				//bdns entry not found
				return [];
			} else {
				return $result;
			}
		}
		
		public function isDomainExists($domain = "sagleft.mfcoin"): bool {
			$result = $this->getDomainInfo($domain);
			//exit(json_encode($result));
			if($result == [] || !isset($result['name'])) {
				return false;
			}
			/* echo "compare:" . "<br/>";
			echo "given:" . "dns:" . $domain . "<br/>";
			echo "blockchain:" . $result['name'] . "<br/>";
			exit; */
			if($result['name'] != "dns:" . $domain) {
				return false;
			}
			//TODO: добавить проверку наличия A-записи
			return true;
		}
	}
	