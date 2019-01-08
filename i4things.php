<?php

if (!extension_loaded('i4things')) {
    class I4THINGS {
/**********************************************************\
|                                                          |
| i4things helpers                                         |
|                                                          |
\**********************************************************/

		private static function server()
		{
			return 'http://server.i4things.com:5408';
		}

		//private const server = 'http://127.0.0.1:5408';

		public static function multi_implode(array $glues, array $array){
			$out = "";
			$g = array_shift($glues);
			$c = count($array);
			$i = 0;
			foreach ($array as $val){
				if (is_array($val)){
					$out .= self::multi_implode($glues,$val);
				} else {
					$out .= (string)$val;
				}
				$i++;
				if ($i<$c){
					$out .= $g;
				}
			}
			return $out;
		}

		public static function multi_explode(array $delimiter,$string){
			$d = array_shift($delimiter);
			if ($d!=NULL){
				$tmp = explode($d,$string);
				foreach ($tmp as $key => $o){
					$out[$key] = self::multi_explode($delimiter,$o);
				}
			} else {
				return $string;
			}
			return $out;
		}
		
		//private const server = 'http://127.0.0.1:5408';
		
		private static function crc($s) {
			$byte_array = array_slice(unpack("C*", "\0".$s), 1);
			$res = 0;
			for($i=0;$i<count($byte_array);$i++) {
				$res = ($res << 1) ^ $byte_array[$i];
				$res = $res & 0xFFFF;
			}
			return sprintf('%04X', $res);
		}

		public static function getchallenge(){
			return (time());// - (int)substr(date('O'),0,3)*60*60); 
		}
		
		
		public static function getkey() {
			$length = 32;
			return substr(str_shuffle(str_repeat($x='0123456789ABCDEF0123456789ABCDEF0123456789ABCDEF', ceil($length/strlen($x)) )),1,$length);
		}
		
		
		public static function guid(){
			if (function_exists('com_create_guid')){
				return com_create_guid();
			}else{
				mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
				$charid = strtoupper(md5(uniqid(rand(), true)));
				$hyphen = chr(45);// "-"
				$uuid = chr(123)// "{"
					.substr($charid, 0, 8).$hyphen
					.substr($charid, 8, 4).$hyphen
					.substr($charid,12, 4).$hyphen
					.substr($charid,16, 4).$hyphen
					.substr($charid,20,12)
					.chr(125);// "}"
				return $uuid;
			}
		}
		
		public static function get($extention, $id, $payload, $key){
			$req = base64_encode(self::encrypt(self::crc($payload).$payload,$key));
			$ch = curl_init(self::server().'/'.$extention.'/'.$id.'-'.$req);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
			$httpres = curl_exec($ch);
			$curl_errno = curl_errno($ch);
			$curl_error = curl_error($ch);
			curl_close($ch);
			
			if ($httpres === NULL){
				// log network error
				return NULL;
			}
    
			return $httpres;
		}
		
/**********************************************************\
|                                                          |
| xxtea.php                                                |
|                                                          |
| XXTEA encryption algorithm library for PHP.              |
|                                                          |
| Encryption Algorithm Authors:                            |
|      David J. Wheeler                                    |
|      Roger M. Needham                                    |
|                                                          |
| Code Author: Ma Bingyao <mabingyao@gmail.com>            |
| LastModified: Mar 2, 2016                                |
|                                                          |
\**********************************************************/
		
        const DELTA = 0x9E3779B9;
        private static function long2str($v, $w) {
            $len = count($v);
            $n = $len << 2;
            if ($w) {
                $m = $v[$len - 1];
                $n -= 4;
                if (($m < $n - 3) || ($m > $n)) return false;
                $n = $m;
            }
            $s = array();
            for ($i = 0; $i < $len; $i++) {
                $s[$i] = pack("V", $v[$i]);
            }
            if ($w) {
                return substr(join('', $s), 0, $n);
            }
            else {
                return join('', $s);
            }
        }
        private static function str2long($s, $w) {
            $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
            $v = array_values($v);
            if ($w) {
                $v[count($v)] = strlen($s);
            }
            return $v;
        }
        private static function int32($n) {
            return ($n & 0xffffffff);
        }
        private static function mx($sum, $y, $z, $p, $e, $k) {
            return ((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ (($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        }
        private static function fixk($k) {
            if (count($k) < 4) {
                for ($i = count($k); $i < 4; $i++) {
                    $k[$i] = 0;
                }
            }
            return $k;
        }
        // $str is the string to be encrypted.
        // $key is the encrypt key. It is the same as the decrypt key.
        private static function encrypt($str, $key) {
            if ($str == "") {
                return "";
            }
            $v = self::str2long($str, true);
            $k = self::fixk(self::str2long($key, false));
            $n = count($v) - 1;
            $z = $v[$n];
            $q = floor(6 + 52 / ($n + 1));
            $sum = 0;
            while (0 < $q--) {
                $sum = self::int32($sum + self::DELTA);
                $e = $sum >> 2 & 3;
                for ($p = 0; $p < $n; $p++) {
                    $y = $v[$p + 1];
                    $z = $v[$p] = self::int32($v[$p] + self::mx($sum, $y, $z, $p, $e, $k));
                }
                $y = $v[0];
                $z = $v[$n] = self::int32($v[$n] + self::mx($sum, $y, $z, $p, $e, $k));
            }
            return self::long2str($v, false);
        }
        // $str is the string to be decrypted.
        // $key is the decrypt key. It is the same as the encrypt key.
        private static function decrypt($str, $key) {
            if ($str == "") {
                return "";
            }
            $v = self::str2long($str, false);
            $k = self::fixk(self::str2long($key, false));
            $n = count($v) - 1;
            $y = $v[0];
            $q = floor(6 + 52 / ($n + 1));
            $sum = self::int32($q * self::DELTA);
            while ($sum != 0) {
                $e = $sum >> 2 & 3;
                for ($p = $n; $p > 0; $p--) {
                    $z = $v[$p - 1];
                    $y = $v[$p] = self::int32($v[$p] - self::mx($sum, $y, $z, $p, $e, $k));
                }
                $z = $v[$n];
                $y = $v[0] = self::int32($v[0] - self::mx($sum, $y, $z, $p, $e, $k));
                $sum = self::int32($sum - self::DELTA);
            }
            return self::long2str($v, true);
        }

	}
/**********************************************************\
|                                                          |
| i4things operations                                      |
|                                                          |
\**********************************************************/

// return array[2] element 0 is facilitator id(GUID), element 1 is the facilitator key
// NULL if error
	function i4things_create_facilitator($rootkey, $name) {
		$facilitatorid = I4THINGS::guid();
		$facilitatorkey = I4THINGS::getkey();
		$res = I4THINGS::get('mc_reg_facilitator', '0', $facilitatorkey.$facilitatorid.$name, $rootkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		if (trim($res) != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return array($facilitatorid, $facilitatorkey);
	}
	
// return array[2] element 0 is account id(GUID), element 1 is the account key 
// NULL if error
	function i4things_create_account($facilitatorid, $facilitatokey, $name) {
		$accountid = I4THINGS::guid();
		$accountkey = I4THINGS::getkey();
		$res = I4THINGS::get('mc_reg_account', $facilitatorid, $accountkey.$accountid.$name, $facilitatokey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		if (trim($res) != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return array($accountid, $accountkey);
	}
	
// return array[2] element 0 is node id(int), element 1 is the node key 
// NULL if error
	function i4things_create_node($accountid, $accountkey, $useaccountkey, $name) {
		$nodekey = '';
		if ($useaccountkey)
		{
			$nodekey = $accountkey;
		}
		else
		{
			$nodekey = I4THINGS::getkey();
		}
		$res = I4THINGS::get('mc_reg_node', $accountid, $nodekey.$name, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$res = trim($res);
		
		if (!is_numeric($res)) {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return array($res, $nodekey);
	}
	
// return array[2] element 0 is gateway id(int), element 1 is the gateway key 	
// NULL if error
	function i4things_create_gateway($accountid, $accountkey, $lat, $lon, $open, $name) {
		$gatewaykey = I4THINGS::getkey();
		$res = I4THINGS::get('mc_reg_gateway', $accountid, $gatewaykey.'#'.$name.'#'.$lat.'#'.$lon.(($open) ? '#1' : '#0'), $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$res = trim($res);
		
		if (!is_numeric($res)) {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return array($res, $gatewaykey);
	}


// NULL if error , 'OK' if success
	function i4things_delete_gateway($accountid, $accountkey, $gatewayid) {
		$res = I4THINGS::get('mc_del_gateway', $accountid, $gatewayid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		$res = trim($res);
		
		if ($res != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return $res;
	}
	
	// NULL if error , 'OK' if success
	function i4things_delete_node($accountid, $accountkey, $nodeid) {
		$res = I4THINGS::get('mc_del_node', $accountid, $nodeid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		$res = trim($res);
		
		if ($res != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return $res;
	}
	
	// NULL if error , 'OK' if success
	function i4things_delete_account($facilitatorid, $facilitatorkey, $accountid) {
		$res = I4THINGS::get('mc_del_account', $facilitatorid, $accountid, $facilitatorkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		$res = trim($res);
		
		if ($res != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return $res;
	}
	
	// NULL if error , 'OK' if success
	function i4things_delete_facilitator($rootkey, $facilitatorid) {
		$res = I4THINGS::get('mc_del_facilitator', '0', $facilitatorid, $rootkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
	
		$res = trim($res);
		
		if ($res != 'OK') {
			// log operation error - eg. wrong key, wrong id format	, duplicated id
			return NULL;
		}
		
		// log all OK
	  return $res;
	}
	
// array with account id's	
// NULL on error
	function i4things_get_facilitator($rootkey) {
		$res = I4THINGS::get('mc_get_facilitator', '0', I4THINGS::getchallenge(), $rootkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = I4THINGS::multi_explode(array(';',','),$res);
	
      // log all OK
	  return $ret;
	}
	
// array with account id's	
// NULL on error 
	function i4things_get_account($facilitatorid, $facilitatokey) {
		$res = I4THINGS::get('mc_get_account', $facilitatorid, I4THINGS::getchallenge(), $facilitatokey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = I4THINGS::multi_explode(array(';',','),$res);
	
      // log all OK
	  return $ret;
	}
	
// array with gateway id's	
// NULL on error 
	function i4things_get_gateway($accountid, $accountkey) {
		$res = I4THINGS::get('mc_get_gateway', $accountid, I4THINGS::getchallenge(), $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = I4THINGS::multi_explode(array(';',','),$res);
	
      // log all OK
	  return $ret;
	}	
	
// array with node id's	
// NULL on error 
	function i4things_get_node($accountid, $accountkey) {
		$res = I4THINGS::get('mc_get_node', $accountid, I4THINGS::getchallenge(), $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = I4THINGS::multi_explode(array(';',','),$res);
	
      // log all OK
	  return $ret;
	}
	
// array with account id's	
// NULL on error
	function i4things_get_facilitator_details($rootkey, $facilitatorid) {
		$res = I4THINGS::get('mc_get_facilitator_details', '0', $facilitatorid, $rootkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = explode(",",$res);
	
      // log all OK
	  return $ret;
	}
	
// array with account id's	
// NULL on error 
	function i4things_get_account_details($facilitatorid, $facilitatokey, $accountid) {
		$res = I4THINGS::get('mc_get_account_details', $facilitatorid, $accountid, $facilitatokey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = explode(",",$res);
	
      // log all OK
	  return $ret;
	}
	
// array with gateway id's	
// NULL on error 
	function i4things_get_gateway_details($accountid, $accountkey, $gatewayid) {
		$res = I4THINGS::get('mc_get_gateway_details', $accountid, $gatewayid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = explode(",",$res);
	
      // log all OK
	  return $ret;
	}	
	
// array with node id's	
// NULL on error 
	function i4things_get_node_details($accountid, $accountkey, $nodeid) {
		$res = I4THINGS::get('mc_get_node_details', $accountid, $nodeid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return NULL;
		}
		
		$ret = explode(",",$res);
	
      // log all OK
	  return $ret;
	}	
	
// return gateway data or error
// example return:
//var gatewayId = "4100";
//var gatewayDayLabels = [1546973755096,1546973695100,1546973635187,1546973575081,1546973515082,1546973455170,1546973395163,1546973335158,1546973275266,1546973215146,1546973155140,1546973095092,1546973035078,1546972975123,1546972915117,1546972855119,1546972795108,1546972735069,1546972675195,1546972615194,1546972555082,1546972495076,1546972435172,1546972375167,1546972315152,1546972255160,1546972195070,1546972135147,1546972075258,1546972015132,1546971955130,1546971895123,1546971835061,1546971775114,1546971715102,1546971655200,1546971595093,1546971535086,1546971474466,1546971474466,1546971438802,1546971378010,1546971378009];
//var gatewayDayHumidity = [79,53,58,51,62,43,75,43,59,57,52,58,74,41,75,43,59,75,74,45,58,46,44,43,76,78,52,78,54,72,73,44,43,52,48,46,47,55,51,59,47,69,50];
//var gatewayDayTemp = [16.7,18,10.7,16.7,21.1,15.1,21.1,12.9,19.2,15.8,17,22.7,20.8,19.5,22,10.7,17,18.3,10.1,17,14.5,21.7,12.3,17.6,16.7,17.6,14.2,20.2,22,11.1,16.4,13.6,20.8,19.8,15.8,19.8,21.7,20.5,16.4,17.3,13.3,11.1,16.7];
//var gatewayHistLabels = [];
//var gatewayHistHumidity = [];
//var gatewayHistTemp = [];

	function i4things_get_gateway_data($accountid, $accountkey, $gatewayid) {
		$res = I4THINGS::get('mc_iot_gateway_data', $accountid, $gatewayid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return '"WRNG0[NETWORK]';
		}
	
		$res = trim($res);
		
	    return $res;
	}
	
// return gateway data or error
// example return
//var deviceId = 1;
//var deviceDayLabels = [1546973675123,1546973555083,1546973435100,1546973315075,1546973195128,1546973075076,1546972955156,1546972835147,1546972715072,1546972595126,1546972475066,1546972355113,1546972235090,1546972115063,1546971995164,1546971875155,1546971790090];
//var deviceDayRssi = [68,72,68,68,72,73,82,82,82,82,82,83,81,82,82,78,80];
//var deviceDayLat = [51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939,51.438939];
//var deviceDayLon = [-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863,-0.21863];

	
	function i4things_get_node_data($accountid, $accountkey, $nodeid) {
		$res = I4THINGS::get('mc_iot_data', $accountid, $nodeid, $accountkey);
		
		if ($res === NULL) {
			// log network/server error
			return '"WRNG0[NETWORK]';
		}
	
		$res = trim($res);
		
	    return $res;
	}
	
	
}

?>