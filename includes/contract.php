<?php
	class Contract extends DatabaseObject {
		protected static $tableName = "contracts";
		
		public function set($playerId, $from, $to) {
			$this->player = $playerId;
			$this->date_of_reg = $from;
			$this->expiry = $to;
		}
	}
