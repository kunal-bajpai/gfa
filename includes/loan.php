<?php
	class Loan extends DatabaseObject {
		protected static $tableName = "loans";
		
		public function set($playerId, $teamId, $from, $to) {
			$this->player = $playerId;
			$this->team = $teamId;
			$this->start = $from;
			$this->end = $to;
		}
	}
