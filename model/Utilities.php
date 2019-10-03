<?php
	namespace App\Model;
	
	class Utilities {
		function isJson($string): bool {
			return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
		}
		
		function data_filter($string = "", $db_link = null) {
			//\App\Model\Utilities::data_filter
			$string = strip_tags($string);
			$string = stripslashes($string);
			$string = htmlspecialchars($string);
			$string = trim($string);
			if(isset($db_link) && $db_link != null) {
				$string = $db_link->filter_string($string);
			}
			return $string;
		}
		
		function cURL($url, $ref, $header, $cookie, $p=null){
			$curlDefault = true;
			//чтобы тестировать на сервере, на котором нет guzzle
			if($curlDefault) {
				$ch =  curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				if(isset($_SERVER['HTTP_USER_AGENT'])) {
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				}
				if($ref != '') {
					curl_setopt($ch, CURLOPT_REFERER, $ref);
				}
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				if($cookie != '') {
					curl_setopt($ch, CURLOPT_COOKIE, $cookie);
				}
				if ($p) {
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
				}
				$result =  curl_exec($ch);
				curl_close($ch);
				if ($result){
					return $result;
				} else {
					return '';
				}
			} else {
				try {
					$client = new \GuzzleHttp\Client();
					if($p != null) {
						parse_str($p, $params);
						$request = $client->post($url, [], [
							'body' => $params
						]);
					} else {
						$request = $client->get($url);
					}
					return $request->getbody();
				} catch(Exception $e) {
					//TODO: обработку ошибки
					//можно обернуть в json
					echo 'guzzle error: ' . $e->getMessage();
				}
			}
		}
		
		function curl_get($url) {
			return \App\Utilities::cURL($url, '', '', '');
		}
		
		function generateCode($length = 6): string {
			// \App\Model\Utilities::generateCode
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;
			while (strlen($code) < $length) {
				$code .= $chars[mt_rand(0, $clen)];
			}
			return $code;
		}
		
		//кажется, return: mixed
		function checkFields($arr = [], $keysArr = [], $errCode = "error", $db_link = null, $ignore_errors = false) {
			$data = [];
			foreach ($keysArr as $key) {
				if(!isset($arr[$key]) || (empty($arr[$key]) && $arr[$key] != "0" && $arr[$key] != 0)) {
					if(!$ignore_errors) {
						exit($errCode.' ('.$key.' is empty)');
					}
				} else {
					$data[$key] = \App\Model\Utilities::data_filter($arr[$key], $db_link);
				}
			}
			return $data;
		}
		
		function checkINT($value = 0, $db_link = null): int {
			$value = \App\Model\Utilities::data_filter($value, $db_link) + 0;
			if(!is_int($value)) {
				$value = 0;
			}
			return $value;
		}
		
		function checkFloat($value = 0, $db_link = null): float {
			$value = floatval(\App\Model\Utilities::data_filter($value, $db_link));
			if(!is_float($value)) {
				$value = 0;
			}
			return $value;
		}
		
		function checkINTFields($arr = [], $keysArr = [], $db_link = null): array {
			//$db_link - ссылка на экземпляр \App\Model\DataBase
			$data = [];
			foreach ($keysArr as $key) {
				if(!isset($arr[$key]) || empty($arr[$key])) {
					$data[$key] = 0;
				} else {
					$data[$key] = \App\Model\Utilities::checkINT($arr[$key], $db_link);
				}
			}
			return $data;
		}
	}
	