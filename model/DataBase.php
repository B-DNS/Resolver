<?php
	namespace App\Model;
	
	class DataBase {
		private $db_link = null;
		
		public function __construct() {
			$this->db_link = new \mysqli(getenv('db_host'), getenv('db_user'), getenv('db_pass'), getenv('db_name'));
			$this->db_link->set_charset("utf8");
		}
		
		public function filter_string($string) {
			return $this->db_link->real_escape_string($string);
		}
		
		public function query($sql_query, $unbuffered = false) {
			return $this->db_link->query($sql_query);
		}
		
		public function query2arr($sql_query) {
			$result = $this->query($sql_query);
			if($result->num_rows > 0) {
				return $result->fetch_assoc();
			} else {
				return [];
			}
		}
		
		public function query2multiArr($sql_query) {
			$arr = [];
			$result = $this->query($sql_query);
			$count = $result->num_rows;
			$i = 0;
			if($count > 0) {
				while($row = $result->fetch_assoc()) {
					$arr[$i] = $row;
					if(array_key_exists('amount', $row)) {
						$arr[$i]['amount'] = rtrim(rtrim($arr[$i]['amount'], '0'), '.');
					}
					$i++;
				}
			}
			return [
				'count' => $count,
				'array' => $arr
			];
		}
		
		public function checkRowExists($sql_query): bool {
			$result = $this->query($sql_query);
			if($result == false) {
				return false;
			} else {
				if($result->num_rows > 0) {
					return true;
				} else {
					return false;
				}
			}
		}
		
		public function checkRowsLimit($sql_query, $limit = 5): bool {
			$result = $this->query($sql_query);
			if($result->num_rows > $limit) {
				//превышен лимит кол-ва записей
				return false;
			} else {
				return true;
			}
		}
		
		public function getRowsCount($sql_query): int {
			$result = $this->query($sql_query);
			return $result->num_rows;
		}
		
		public function tryQuery($sql_query): bool {
			//проводим запрос без ожидания ответа
			$result = $this->query($sql_query);
			if(is_bool($result) === true) {
				//strict
				return $result;
			} else {
				//по умолчанию для непонятного случая
				return false;
			}
		}
	}
	