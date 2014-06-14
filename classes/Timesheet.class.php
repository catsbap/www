<?php


require_once("DataObject.class.php");

class Timesheet extends DataObject {
	protected $data = array(
		//these fields are in the timesheet table.
		"timesheet_id"=>"",
		"timesheet_approved"=>"",
		"timesheet_submitted"=>"",
		"timesheet_start_date"=>"",
		"timesheet_end_date"=>"",
		"person_id"=>""
	);
	
	
	
	
	//get all of the timesheet ids for a specific person
	public function getTimesheetIds($person_id) {
		$conn=parent::connect();
		$sql="SELECT distinct(timesheet_id), timesheet_start_date, timesheet_end_date FROM " . TBL_TIMESHEET . " WHERE person_id = :person_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();
			$timesheet=array();
			foreach ($st->fetchAll() as $row) {
				$timesheet[] = new Timesheet($row);
			}
			parent::disconnect($conn);
			return $timesheet;
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
	
	//get all of the timesheet info for a specific timesheet. This should probably be renamed
	public function getTimesheetById($person_id, $timesheet_start_date, $timesheet_end_date) {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_TIMESHEET . " WHERE person_id = :person_id and timesheet_start_date = :timesheet_start_date and timesheet_end_date = :timesheet_end_date";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_start_date", date('y-m-d', strtotime($timesheet_start_date)), PDO::PARAM_STR);
			$st->bindValue(":timesheet_end_date", date('y-m-d', strtotime($timesheet_end_date)), PDO::PARAM_STR);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();
			$timesheet=array();
			foreach ($st->fetchAll() as $row) {
				$timesheet[] = new Timesheet($row);
			}
			parent::disconnect($conn);
			if (count($timesheet) > 0) {
				return $timesheet;
			} else {
				return 0;
			}
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
	
	//get all of the timesheet info for a specific timesheet. This should probably be renamed since it doesn't just get dates
	public function getTimesheetDatesByTimesheetId($timesheet_id) {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_TIMESHEET . " WHERE timesheet_id = :timesheet_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_id", $timesheet_id, PDO::PARAM_INT);
			$st->execute();
			$timesheet=array();
			foreach ($st->fetchAll() as $row) {
				$timesheet[] = new Timesheet($row);
			}
			parent::disconnect($conn);
			if (count($timesheet) > 0) {
				return $timesheet;
			} else {
				return 0;
			}
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}

			
	//function inserts new timesheet into db and returns the autoincrement field so we can update the timesheet_item table with the key 	
	public function insertTimesheet($person_id, $timesheet_start_date, $timesheet_end_date) {
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_TIMESHEET . " (
			timesheet_id,
			timesheet_approved,
			timesheet_submitted,
			timesheet_start_date,
			timesheet_end_date,
			person_id
			) VALUES (
			:timesheet_id,
			:timesheet_approved,
			:timesheet_submitted,
			:timesheet_start_date,
			:timesheet_end_date,
			:person_id
			)";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_id", 'auto', PDO::PARAM_INT);
			$st->bindValue(":timesheet_approved", $this->data["timesheet_approved"], PDO::PARAM_INT);
			$st->bindValue(":timesheet_submitted", $this->data["timesheet_submitted"], PDO::PARAM_INT);
			$st->bindValue(":timesheet_start_date", date('y-m-d', strtotime($timesheet_start_date)), PDO::PARAM_STR);
			$st->bindValue(":timesheet_end_date", date('y-m-d', strtotime($timesheet_end_date)), PDO::PARAM_STR);
			//error_log("here is the END DATE: ") . date('y-m-d', strtotime($this->data["timesheet_end_date"]));
			//error_log("here is the START DATE: ") . date('y-m-d', strtotime($this->data["timesheet_start_date"]));
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();
			$sql = "SELECT LAST_INSERT_ID() FROM " . TBL_TIMESHEET;
			$st = $conn->prepare($sql);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert of timesheet, sql is $sql " . $e->getMessage());
		}	
	}
	
	//this is old, the last insert ID is retrieved using MySQL now.
	public function getLastInsert() {
		$conn=parent::connect();
		$sql = "SELECT timesheet_id FROM " . TBL_TIMESHEET . " WHERE timesheet_start_date = :timesheet_start_date and timesheet_end_date = :timesheet_end_date and person_id = :person_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_start_date", date('y-m-d', strtotime($this->data["timesheet_start_date"])), PDO::PARAM_STR);
			$st->bindValue(":timesheet_end_date", date('y-m-d', strtotime($this->data["timesheet_end_date"])), PDO::PARAM_STR);
			$st->bindValue(":person_id", $this->data["person_id"], PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the increment value: " . $e->getMessage() . " sql is " . $sql);
		}
	}
	
//update the timesheet data.
	public function updateTimesheet($timesheet_start_date, $timesheet_end_date) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TIMESHEET . " SET
			person_id = :person_id,
			timesheet_approved = :timesheet_approved,
			timesheet_submitted = :timesheet_submitted,
			timesheet_start_date = :timesheet_start_date,
			timesheet_end_date = :timesheet_end_date
			WHERE timesheet_start_date = :timesheet_start_date and timesheet_end_date = :timesheet_end_date";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $this->data["person_id"], PDO::PARAM_STR);
			$st->bindValue(":timesheet_approved", $this->data["timesheet_approved"], PDO::PARAM_STR);
			$st->bindValue(":timesheet_submitted", $this->data["timesheet_submitted"], PDO::PARAM_INT);
			$st->bindValue(":timesheet_start_date", date('y-m-d', strtotime($this->data["timesheet_start_date"])), PDO::PARAM_STR);
			$st->bindValue(":timesheet_end_date", date('y-m-d', strtotime($this->data["timesheet_end_date"])), PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update of timesheet, sql is $sql " . $e->getMessage());
		}

	}
	
	//submit the timesheet.
	public function submitTimesheet($timesheet_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TIMESHEET . " SET
			timesheet_submitted = 1
			WHERE timesheet_id = :timesheet_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_id", $timesheet_id, PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update of timesheet, sql is $sql " . $e->getMessage());
		}

	}

//approve the timesheet.
	public function approveTimesheet($timesheet_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TIMESHEET . " SET
			timesheet_submitted = 1, timesheet_approved = 1
			WHERE timesheet_id = :timesheet_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_id", $timesheet_id, PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update of timesheet, sql is $sql " . $e->getMessage());
		}

	}


	public function deleteTimesheet($timesheet_id) {
		$conn=parent::connect();
		$sql = "DELETE FROM " . TBL_TIMESHEET . " WHERE timesheet_id = :timesheet_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":timesheet_id", $timesheet_id, PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update of timesheet item: " . $e->getMessage());
		}
	}
	
	//get out all of the timesheet dates so we can display the unsubmitted by date
	public function getTimesheetDates() {
		$conn=parent::connect();
		//this is going to break something but we can't sql this way.
		//$sql = "select distinct(timesheet_start_date), timesheet_end_date, timesheet_id FROM " . TBL_TIMESHEET;
		$sql = "select distinct(timesheet_start_date), timesheet_end_date FROM " . TBL_TIMESHEET;
		try {
			$st = $conn->prepare($sql);
			$st->execute();	
			foreach ($st->fetchAll() as $row) {
				$timesheet[] = new Timesheet($row);
			}
			return $timesheet;
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update of timesheet item: " . $e->getMessage());
		}
	}


}

?>