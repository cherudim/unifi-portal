<?php

	class Config {
		protected $config = array();

		public function __construct($file = 'config.ini') {
			$this->config = parse_ini_file(dirname(dirname(__FILE__)) . '/' . $file, true);
		}

		public function Get($Domain, $Field) {
			if($this->Has($Domain, $Field)) {
				return $this->config[$Domain][$Field];
			}
			throw new Exception('Undefined variable ' . $Domain . ':' . $Field);
		}

		public function Has($Domain, $Field) {
			return isset($this->config[$Domain][$Field]);
		}
	}

?>