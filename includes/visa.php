<?php
	class Visa extends DatabaseObject {
		protected static $tableName = "visas";
		
		public function __construct(){
			parent::__construct();
		}
		
		public function set($playerId, $to) {
			$this->player = $playerId;
			$this->added_on = time();
			$this->expiry = $to;
		}
	}
