<?php
	class Player extends DatabaseObject {
		protected static $tableName = "players";
		
		public function find_contract_team() {
			$conts = Contract::find_by_sql("SELECT * FROM contracts WHERE player={$this->id} AND expiry>".time()." AND term=0 ORDER BY date_of_reg DESC");
			if(isset($conts))
				return Team::find_by_id($conts[0]->team);
			else
				return NULL;
		}
		
		public function find_loan_team() {
			$loanedTo = Team::find_by_sql("SELECT * FROM teams WHERE id = (SELECT toteam FROM loans WHERE player = {$this->id} AND end > ".time().")");
			if(isset($loanedTo[0]))
				return $loanedTo[0];
			else
				return NULL;
		}
		
		public function find_current_team() {
			$loanTeam = $this->find_loan_team();
			if(!isset($loanTeam))
				return $this->find_contract_team();
			else
				return $loanTeam;
		}
	}
