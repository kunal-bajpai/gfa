<?php
	class Transfer extends DatabaseObject {
		protected static $tableName = "transfers";
		
		public function set($playerId, $assocId, $from) {
			$this->player = $playerId;
			$this->assoc = $assocId;
			$this->start = $from;
		}
	}
