<?php
	class Loan extends DatabaseObject {
		protected static $tableName = "loans";
		
		public function set($playerId, $fromTeamId, $toTeamId, $from, $to) {
			$this->player = $playerId;
			$this->fromteam = $fromTeamId;
			$this->toteam = $toTeamId;
			$this->start = $from;
			$this->end = $to;
		}
	}
