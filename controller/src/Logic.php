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
		
		public function domain_error() {
			http_response_code(404);
			exit("nx");
		}
		
		public function get_domain($domain = "sagleft.mfcoin", $maxIPs = -1, $need_shuffle = false): array {
			if($maxIPs == -1) {
				$maxIPs = 20;
			}
			$entry_name = "dns:" . $domain;
			$result = $this->mfcoin->NVS_nameData($entry_name);
			/* {"name":"dns:sagleft.mfcoin",
			"value":"A=176.57.210.39|CNAME=saleft.ru",
			"txid":"551c34486c688d813aa8c4e88d0bbe6f0da895087912d7a4ed7833d050350431",
			"address":"MsygnR8pucrDmyzT6wbyBiPJCY3kkduc13",
			"expires_in":1912062,
			"expires_at":1923819,
			"time":1570138118} */
			if($result == []) {
				return [];
			}
			$entry_value = $result['value'];
			if($entry_value == "") {
				return [];
			}
			//разбираем данные NVS записи
			$dns_lines = explode("|", $entry_value);
			//exit($dns_lines);
			if(count($dns_lines) == 0) {
				return [];
			}
			//парсинг значений
			$IPs_arr = [];
			for($i=0; $i < count($dns_lines); $i++) {
				$dns_line = $dns_lines[$i];
				$line_arr = explode("=", $dns_line);
				if(count($line_arr) == 0) {
					return []; //parse error
				}
				if($line_arr[0] == "A") {
					//найдена A-запись
					$dns_IPs = explode(";", $line_arr[1], $maxIPs);
					//парсим IPшники
					if(count($dns_IPs) == 0) {
						return []; //parse error
					}
					for($j=0; $j < count($dns_IPs); $j++) {
						$IPs_arr[] = $dns_IPs[$j];
					}
					break;
				}
			}
			if($IPs_arr == []) {
				//ничего не найдено
				return [];
			}
			if(count($IPs_arr) > 1 && $need_shuffle) {
				//перемешиваем IPшники, если необходимо
				shuffle($IPs_arr);
			}
			$IPs_list = implode("\r\n", $IPs_arr);
			return [
				'domain'   => $domain,
				'nvs_name' => $result['name'],
				'ip_list'  => $IPs_list
			];
		}
	}
	