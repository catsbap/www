<?php


//connectivity
require_once("DataObject.class.php");

class Task extends DataObject {
	protected $data = array(
		//these fields are in the tasks table.
		"task_id"=>"",
		"task_name"=>"",
		"task_hourly_rate"=>"",
		"task_bill_by_default"=>"",
		"task_common"=>"",
		"task_archived"=>""
	);
	
	//display all information about a task. returned as an array. This function returns all tasks, archived and not, based on value passed in.
	public static function getTasks($archive_flag) {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_TASK . " WHERE task_archived = :archive_flag";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":archive_flag", $archive_flag, PDO::PARAM_INT);
			$st->execute();
			foreach ($st->fetchAll() as $row) {
				$tasks[] = new Task($row);
			}
			parent::disconnect($conn);
			return array($tasks);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
		
	//return all data for a task client based on the task_id.
	//*OK, I'll be honest here. This query makes no sense, but I'll leave it here, because clearly I am either
	//crazy or tired.
	public static function getTask($task_id) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_TASK . " WHERE task_id = :task_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Task($row);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//function returns the individual common tasks.
	public static function getCommonTasks() {
		$conn=parent::connect();
		$sql = "SELECT distinct(task_name), task_id FROM " . TBL_TASK . " WHERE task_common = 1";
	
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			foreach ($st->fetchAll() as $row) {
				$task[] = new Task($row);
			}
			parent::disconnect($conn);
			return array($task);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}	
	}
	
	//function inserts new task into db. 
	
	public function insertTask() {
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_TASK . " (
			task_name,
			task_hourly_rate,
			task_bill_by_default,
			task_common
			) VALUES (
			:task_name,
			:task_hourly_rate,
			:task_bill_by_default,
			:task_common
			)";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_name", $this->data["task_name"], PDO::PARAM_STR);
			$st->bindValue(":task_hourly_rate", $this->data["task_hourly_rate"], PDO::PARAM_STR);
			$st->bindValue(":task_bill_by_default", $this->data["task_bill_by_default"], PDO::PARAM_INT);
			$st->bindValue(":task_common", $this->data["task_common"], PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert, sql is $sql " . $e->getMessage());
		}	
	}



	public function getTaskId($task_name) {
		$conn=parent::connect();
		$sql = "SELECT task_id FROM " . TBL_TASK . " WHERE task_name = :task_name";			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_name", $task_name, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the task id, sql is $sql " . $e->getMessage());
		}
	}
	
	public function getTaskName($task_id) {
		$conn=parent::connect();
		$sql = "SELECT task_name FROM " . TBL_TASK . " WHERE task_id = :task_id";			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the task id, sql is $sql " . $e->getMessage());
		}
	}
	
	//return all data for a task based on the task_id.
	public static function getTaskById($task_id) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_TASK . " WHERE task_id = :task_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Task($row);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//return the hourly rate for the task.
	public static function getHourlyRate($task_id) {
		$conn=parent::connect();
		$sql = "SELECT task_hourly_rate FROM " . TBL_TASK . " WHERE task_id = :task_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row['task_hourly_rate'] == NULL) {
				return 0;
			} else {
				return $row['task_hourly_rate'];
			}
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//update the task's information based on the task_id.	
	public function updateTask($task_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TASK . " SET
				task_name = :task_name,
				task_hourly_rate = :task_hourly_rate,
				task_bill_by_default = :task_bill_by_default,
				task_common = :task_common,
				task_archived = :task_archived
				WHERE task_id = :task_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":task_name", $this->data["task_name"], PDO::PARAM_STR);
				$st->bindValue(":task_hourly_rate", $this->data["task_hourly_rate"], PDO::PARAM_STR);
				$st->bindValue(":task_bill_by_default", $this->data["task_bill_by_default"], PDO::PARAM_STR);
				$st->bindValue(":task_common", $this->data["task_common"], PDO::PARAM_STR);
				$st->bindValue(":task_archived", $this->data["task_archived"], PDO::PARAM_STR);
				$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on update of task: " . $e->getMessage() . " sql is " . $sql);
			}
	}

//update the task's hourly rate field.	
	public function updateTaskHourlyRate($task_id, $task_hourly_rate) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TASK . " SET
				task_hourly_rate = :task_hourly_rate
				WHERE task_id = :task_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":task_hourly_rate", $task_hourly_rate, PDO::PARAM_INT);
				$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on update of task: " . $e->getMessage() . " sql is " . $sql);
			}
	}

	
	//archive this task, you also have to remove it from project_task.	
	public function archiveTask($archive_flag, $task_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_TASK . " SET
				task_archived = :archive_flag WHERE task_id = :task_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":archive_flag", $archive_flag, PDO::PARAM_INT);
				$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on archive of task: " . $e->getMessage() . " sql is " . $sql);
			}
	}


}

?>