<?php
	class Contract extends DatabaseObject {
		protected static $tableName = "contracts";
		
		public function set($playerId, $teamId, $from, $to) {
			$this->player = $playerId;
			$this->team = $teamId;
			$this->date_of_reg = $from;
			$this->expiry = $to;
		}
	}
