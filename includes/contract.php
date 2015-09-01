<?php
	class Contract extends DatabaseObject {
		protected static $tableName = "contracts";
		
		public function __construct(){
			parent::__construct();
		}
		
		public function set($playerId, $from, $to) {
			$this->player = $playerId;
			$this->date_of_reg = $from;
			$this->expiry = $to;
		}
	}
