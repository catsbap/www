<?php


//connectivity
require_once("DataObject.class.php");

class Project_Task extends DataObject {
	protected $data = array(
		//these fields are in the project_person table.
		"project_id"=>"",
		"task_id"=>"",
		"total_budget_hours"=>""
	);
	
	//function returns all of the tasks associated with a given project (archived and not).
	public static function getTasksForProject($project_id) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_TASK . " WHERE task_id IN (SELECT task_id FROM " . TBL_PROJECT_TASK . " WHERE project_id = :project_id)";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":project_id", $project_id, PDO::PARAM_INT);
			$st->execute();
			$tasks=array();
			foreach ($st->fetchAll() as $row) {
				$tasks[] = new Task($row);
			}
			$row=$st->fetch();
			parent::disconnect($conn);
			return array($tasks);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed returning the tasks for the project: " . $e->getMessage() . "query is " . $sql);
		}
	}
	
	//delete the rows in the table for a given project.
	public function deleteProjectTask($project_id) {
		$conn=parent::connect();
		$sql = "DELETE FROM " . TBL_PROJECT_TASK . " WHERE project_id = :project_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":project_id", $project_id, PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on delete of project_task, sql is $sql " . $e->getMessage());
		}	
	}

	
	public function insertProjectTask($task_id, $project_id, $budget_hours) {
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_PROJECT_TASK . " (
			project_id,
			task_id,
			total_budget_hours
			) VALUES (
			:project_id,
			:task_id,
			:total_budget_hours
			)";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":project_id", $project_id, PDO::PARAM_INT);
			$st->bindValue(":task_id", $task_id, PDO::PARAM_INT);
			$st->bindValue(":total_budget_hours", $budget_hours, PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert of project_task, sql is $sql " . $e->getMessage());
		}	
	}
	
	public function updateProjectTask($task_id, $project_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_PROJECT_TASK . " SET
				project_id = :project_id,
				task_id = :task_id,
				total_budget_hours = :total_budget_hours
				WHERE project_id = :project_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":project_id", $this->data["project_id"], PDO::PARAM_STR);
				$st->bindValue(":task_id", $this->data["task_id"], PDO::PARAM_STR);
				$st->bindValue(":total_budget_hours", $this->data["total_budget_hours"], PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on project task update: " . $e->getMessage() . " sql is " . $sql);
			}
	}		
}
	
	/*public static function getEnumValues($colName) {
		$conn=parent::connect();
		$sql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . TBL_PROJECT . "' AND COLUMN_NAME = :colName";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":colName", $colName, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage() . " sql is " . $sql);
		}
	}

	
	//return the clients name based on the client_id.
	//I'll keep this here as a utility function in case we need it.
	public function getClientNameById($client_id) {
		$conn=parent::connect();
		$sql = "SELECT client_name FROM " . TBL_CLIENT . " WHERE client_id = '" . $client_id . "'";			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the client name, sql is $sql " . $e->getMessage());
		}
	}
	
	public function getClientId($client_name) {
		$conn=parent::connect();
		$sql = "SELECT client_id FROM " . TBL_CLIENT . " WHERE client_name = '" . $client_name . "'";			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_name", $client_name, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the client id, sql is $sql " . $e->getMessage());
		}
	}
	
	//get the available currencies out of the currency table
	//9/4: Client only needs US here, so this only returns USD at this point, but this is built to handle others.
	public function getCurrency() {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_CURRENCY;
		
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			parent::disconnect($conn);
			$_currency = array();
			foreach ($st->fetchAll() as $row) {
				$currency[] = $row;
			}
			return $currency;
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//get the currency for a specific currency index
	public function getCurrencyByIndex($client_currency_index) {
		$conn=parent::connect($client_currency_index);
		$sql = "SELECT client_preferred_currency FROM " . TBL_CURRENCY . " WHERE client_currency_index = :client_currency_index";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_currency_index", $client_currency_index, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			//just return the value for the currency.
			if ($row) return $row[0];
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
		
	//update the client record based on the client_id
	//if we want to break out the address, write the config to do the update later
	//so that we can update those fields as well.
	//9/4/13
	/*public function updateClient($client_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_CLIENT . " SET
				client_name = :client_name,
				client_email = :client_email,
				client_phone = :client_phone,
				client_fax = :client_fax,
				client_currency_index = :client_currency_index,
				client_logo_link = :client_logo_link,
				client_archived = :client_archived
				WHERE client_id = :client_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":client_name", $this->data["client_name"], PDO::PARAM_STR);
				$st->bindValue(":client_email", $this->data["client_email"], PDO::PARAM_STR);
				$st->bindValue(":client_phone", $this->data["client_phone"], PDO::PARAM_INT);
				$st->bindValue(":client_fax", $this->data["client_fax"], PDO::PARAM_INT);
				$st->bindValue(":client_archived", $this->data["client_archived"], PDO::PARAM_INT);
				$st->bindValue(":client_currency_index", $this->data["client_currency_index"], PDO::PARAM_INT);
				//NO NO NO THIS IS TOO HARDCODED!!
				if ($this->data["client_logo_link"]) {
					$st->bindValue(":client_logo_link", "images/" . $this->data["client_logo_link"], PDO::PARAM_STR);
				} else {
					$st->bindValue(":client_logo_link", "images/default.jpg", PDO::PARAM_STR);
				}				
				$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on update: " . $e->getMessage());
			}
	}
	
	//update the archive flag in the client table.
	public function setArchiveFlag($flag, $client_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_CLIENT . " SET
			client_archived = :client_archived
			WHERE client_id = :client_id";
	try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->bindValue(":client_archived", $flag, PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update: " . $e->getMessage());
		}

	}
	
	//get the archive flag out of the client table.
	public function getArchiveFlag($client_id) {
		$conn=parent::connect($client_id);
		$sql = "SELECT client_archived FROM " . TBL_CLIENT . " WHERE client_id = :client_id";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row[0];
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	
	//get the archive flag out of the client table.
	public function deleteClient($client_id) {
		//first delete the contacts.
		$conn=parent::connect();
		$sql = "DELETE FROM " . TBL_CONTACT . " WHERE client_id = :client_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on delete of contact rows.: " . $e->getMessage());
		}
		$conn=parent::connect($client_id);
		$sql = "DELETE FROM " . TBL_CLIENT . " WHERE client_id = :client_id";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on delete of client: " . $e->getMessage());
		}
	}
/*	// OLD function stubs below this line.
	//display all client names
	/*public static function getClientNameAndLogo($clientId) {
		$conn=parent::connect();
		$sql="SELECT client_name, client_logo_link FROM " . TBL_CLIENT . " WHERE client_id = :client_id";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $clientId, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Client($row);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
	
	

	public static function getByUserName($userName) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username";
	
	
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":username", $username, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Member($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed: " . $e->getMessage());
		}
	}

	public static function getByEmailAddress($emailAddress) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE emailAddress = :emailAddress";
	
	
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":emailAddress", $emailAddress, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Member($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed: " . $e->getMessage());
		}
	}		
	
	public function getGenres() {
		return $this->_genres;
	}
		
	public function getGenderString() {
		return ($this->data["gender"] == "f") ? "Female" : "Male";
	}
	
	public function getFavoriteGenreString () {
		return ($this->_genres[$this->data["favoriteGenre"]]);
	}
	
	***OLD FUNCTION, CAN PROBABLY REMOVE***
	public function insertClientAddress($clientId) {
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_CLIENT_ADDRESS . " (
			client_id,
			client_address_number,
			client_street_name,
			client_state,
			client_zip,
			client_apartment,
			client_city
			) VALUES (
			:client_id,
			:client_address_number,
			:client_street_name,
			:client_state,
			:client_zip,
			:client_apartment,
			:client_city
			)";
			
	try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $this->data_address["client_id"], PDO::PARAM_STR);
			$st->bindValue(":client_address_number", $this->data_address["client_address_number"], PDO::PARAM_INT);
			$st->bindValue(":client_street_name", $this->data_address["client_street_name"], PDO::PARAM_STR);
			$st->bindValue(":client_state", $this->data_address["client_state"], PDO::PARAM_STR);
			$st->bindValue(":client_zip", $this->data_address["client_zip"], PDO::PARAM_INT);
			$st->bindValue(":client_apartment", $this->data_address["client_apartment"], PDO::PARAM_STR);
			$st->bindValue(":client_city", $this->data_address["client_city"], PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert, sql is $sql " . $e->getMessage());
		}
	}
	
	**OLD FUNCTION CAN PROBABLY REMOVE**
	public function getClientIdByName($client_name) {
		$conn=parent::connect();
		$sql = "SELECT client_id FROM " . TBL_CLIENT . " WHERE client_name = :client_name";			
		try {
			$st = $conn->prepare($sql);
			//$st->bindValue(":client_name", $this->data["client_name"], PDO::PARAM_STR);
			$st->bindValue(":client_name", $client_name, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Client($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert, sql is $sql " . $e->getMessage());
		}
	}
	
	public function update() {
		$conn=parent::connect();
		//set up the encrypted password string here or set it to blank(this didn't work as a variable!)
		$passwordSql = $this->data["password"] ? "password = password(:password)," : "";
		$sql = "UPDATE " . TBL_MEMBERS . " SET
			username = :username,
			password = password(:password),
			firstName = :firstName,
			lastName = :lastName,
			joinDate = :joinDate,
			gender = :gender,
			favoriteGenre = :favoriteGenre,
			emailAddress = :emailAddress,
			otherInterests = :otherInterests,
			admin = :admin 
			WHERE id = :id";
	try {
			$st = $conn->prepare($sql);
			$st->bindValue(":id", $this->data["id"], PDO::PARAM_INT);
			$st->bindValue(":username", $this->data["username"], PDO::PARAM_STR);
			$st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);
			$st->bindValue(":firstName", $this->data["firstName"], PDO::PARAM_STR);
			$st->bindValue(":lastName", $this->data["lastName"], PDO::PARAM_STR);
			$st->bindValue(":joinDate", $this->data["joinDate"], PDO::PARAM_STR);
			$st->bindValue(":gender", $this->data["gender"], PDO::PARAM_STR);
			$st->bindValue(":favoriteGenre", $this->data["favoriteGenre"], PDO::PARAM_STR);
			$st->bindValue(":emailAddress", $this->data["emailAddress"], PDO::PARAM_STR);
			$st->bindValue(":otherInterests", $this->data["otherInterests"], PDO::PARAM_STR);
			$st->bindValue(":admin", $this->data["admin"], PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update: " . $e->getMessage());
		}
	}
	
	//function is part of the member manager application
	public function delete() {
		$conn = parent::connect();
		$sql = "DELETE FROM " . TBL_MEMBERS . " WHERE id = :id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":id", $this->data["id"], PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed: " . $e->getMessage());
		}
	}
		 
	//function added as part of the members area
	public function authenticate() {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username AND password = password(:password)";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":username", $this->data["username"], PDO::PARAM_STR);
			$st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect( $conn );
			if ($row) return new Member($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("query failed: " . $e->getMessage() );
		}
	}*/
?>