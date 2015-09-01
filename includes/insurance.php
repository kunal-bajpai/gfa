<?php
	class Insurance extends DatabaseObject {
		protected static $tableName = "insurances";
		
		public function set($playerId, $to) {
			$this->player = $playerId;
			$this->added_on = time();
			$this->expiry = $to;
		}
	}
