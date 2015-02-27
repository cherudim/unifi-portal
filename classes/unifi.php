<?php

	class UniFi {
		protected $host;
		protected $user;
		protected $password;
		protected $port;
		protected $protocol;

		protected $hash;

		public function __construct($host, $user, $password, $port, $protocol) {
			$this->host = $host;
			$this->user = $user;
			$this->password = $password;
			$this->port = $port;
			$this->protocol = $protocol;

			$this->hash = $this->generateHash();
		}

		protected function generateFQDN() {
			return $this->protocol . '://' . $this->host . (!is_null($this->port) ? ':' . $this->port : '');
		}

		protected function generateHash($length = 8) {
			$chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
			$hash = '';
			for($i = 0; $i < $length; $i++) {
				mt_srand();
				$hash .= $chars[mt_rand(0, count($chars) - 1)];
			}
			return $hash;
		}

		protected function validateMAC($mac) {
			return preg_match('#^[0-9a-f]{2}\:[0-9a-f]{2}\:[0-9a-f]{2}\:[0-9a-f]{2}\:[0-9a-f]{2}\:[0-9a-f]{2}$#', strtolower($mac));
		}

		public function Authorize($mac, $ap, $minutes = 120) {
			if(!$this->validateMAC($mac)) {
				throw new Exception('"' . $mac . '" is not a valid MAC access');
			}

			$this->call('/login', 'login=login&username=' . $this->user . '&password=' . $this->password);

			$data = array(
				'cmd' => 'authorize-guest',
				'mac' => $mac,
				'minutes' => $minutes
			);

			if($this->validateMAC($ap)) {
				$data['ap_mac'] = $ap;
			}
			$this->call('/api/cmd/stamgr', $data);

			$this->call('/logout');

			return true;
		}

		protected function call($uri, $data = null) {
			$ch = curl_init();

			$cookie = '/tmp/unifi_cookie.' . $this->hash; // Cookie file for storing login. Append hash to prevent it from being oerwritten by concurrent requests
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			#curl_setopt($ch, CURLOPT_SSLVERSION, 3);

			curl_setopt($ch, CURLOPT_URL, $this->generateFQDN() . $uri);
			if(!is_null($data)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, (is_array($data) ? 'json=' . json_encode($data) : $data));
			}

			error_log('Calling "' . $this->generateFQDN() . $uri . '" with data: ' . print_r($data, true));

			curl_exec($ch);

			if(curl_errno($ch) != 0) {
				throw new Exception('Curl error #' . curl_errno($ch) . ' while communicating with the UniFi Controller (' . $this->generateFQDN() . $uri . ')!', curl_errno($ch));
			}

			curl_close($ch);
		}
	}

?>