<?php


//connectivity
require_once("DataObject.class.php");

class Person extends DataObject implements JsonSerializable {
	protected $data = array(
		//these fields are in the person table.
		"person_id"=>"",
		"person_username"=>"",
		"person_password"=>"",
		"person_name"=>"",
		"person_first_name"=>"",
		"person_last_name"=>"",
		"person_email"=>"",
		"person_department"=>"",
		"person_hourly_rate"=>"",
		"person_perm_id"=>"",
		"person_type"=>"",
		"person_logo_link" =>""
	);
	
	//seeing if we can just make this easier
	function jsonSerialize() {
        $jsonData = array(
        // Represent the object
        // in the way you want it arranged in the API
        "person_id" => $this->getValueEncoded("person_id"),
        "person_username" => $this->getValueEncoded("person_username"),
        "person_password" => $this->getValueEncoded("person_password"),
        "person_name" => $this->getValueEncoded("person_name"),
        "person_first_name" => $this->getValueEncoded("person_first_name"),
		"person_last_name" => $this->getValueEncoded("person_last_name"),
		"person_email" => $this->getValueEncoded("person_email"),
		"person_department" => $this->getValueEncoded("person_department"),
		"person_hourly_rate" => $this->getValueEncoded("person_hourly_rate"),
		"person_type" => $this->getValueEncoded("person_type"),
		"person_logo_link" => $this->getValueEncoded("person_logo_link")
		);
        return $jsonData;
    }
    
//just starting to work on authentication here... not sure this is where we want to ultimately put this, but for now I'm putting it in the person class.    
	public function authenticate() {
		$conn=parent::connect();
		//we're using the user's email address as the login right now. 
		$sql = "SELECT * FROM " . TBL_PERSON . " WHERE person_email = :person_username AND person_password = password(:person_password)";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_username", $this->data["person_username"], PDO::PARAM_STR);
			$st->bindValue(":person_password", $this->data["person_password"], PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect( $conn );
			if ($row)  return new Person($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("query failed: " . $e->getMessage() );
		}
	}
	//see if this person has set up their password yet. this drives if we resend them an invitation or have them change the password.
	public static function isPasswordSet($person_email) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_PERSON . " WHERE person_email = :person_email AND person_password IS NOT NULL";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return 1;
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}

	//return the hourly rate for the person.
	public static function getHourlyRate($person_id) {
		$conn=parent::connect();
		$sql = "SELECT person_hourly_rate FROM " . TBL_PERSON . " WHERE person_id = :person_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row['person_hourly_rate'] == NULL) {
				return 0;
			} else {
				return $row['person_hourly_rate'];
			}
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//gets people out of the client table. We'll return this as an array and put it in a list once the
	//data is out.
	public static function getPeople() {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_PERSON;
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			foreach ($st->fetchAll() as $row) {
				$people[] = new Person($row);
			}
			parent::disconnect($conn);
			return array($people);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}


//the email address is the user's login. The user is sent an email address and are asked to set up their password before they login. The password is not set here,
//it is set once the user gets the email.
	public function insertPerson() {
	//first insert the parent row
		//removed foreign key on person_permissions. Consider what will happen with permissions!
		//person perms are set in insertPermissions(), in person_permissions.class.sql to keep it separate and consistent.
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_PERSON . " (
			person_first_name,
			person_last_name,
			person_email,
			person_department,
			person_hourly_rate,
			person_perm_id,
			person_type,
			person_logo_link
			) VALUES (
			:person_first_name,
			:person_last_name,
			:person_email,
			:person_department,
			:person_hourly_rate,
			:person_perm_id,
			:person_type,
			:person_logo_link
			)";
			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_first_name", $this->data["person_first_name"], PDO::PARAM_STR);
			$st->bindValue(":person_last_name", $this->data["person_last_name"], PDO::PARAM_STR);
			$st->bindValue(":person_email", $this->data["person_email"], PDO::PARAM_STR);
			$st->bindValue(":person_department", $this->data["person_department"], PDO::PARAM_STR);
			$st->bindValue(":person_hourly_rate", $this->data["person_hourly_rate"], PDO::PARAM_INT);
			$st->bindValue(":person_perm_id", $this->data["person_perm_id"], PDO::PARAM_STR);
			$st->bindValue(":person_type", $this->data["person_type"], PDO::PARAM_STR);
			$st->bindValue(":person_logo_link", basename($this->data["person_logo_link"]), PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on insert of person, sql is $sql " . $e->getMessage());
		}
	}

//returns the data for a person based on the person_id.
	public static function getPersonById($person_id) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_PERSON . " WHERE person_id = :person_id";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Person($row);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
		
	public static function getByUserName($person_username) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_PERSON . " WHERE person_username = :person_username";
	
	
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_username", $person_username, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Person($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed: " . $e->getMessage());
		}
	}

	public static function getByEmailAddress($person_email) {
		$conn=parent::connect();
		$sql = "SELECT * FROM " . TBL_PERSON . " WHERE person_email = :person_email";
	
	
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Person($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed: " . $e->getMessage());
		}
	}
	//function returns the individual person types
	public static function getPersonTypes() {
		$conn=parent::connect();
		$sql = "SELECT distinct(person_type) FROM " . TBL_PERSON;
	
	
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			$person=array();
			foreach ($st->fetchAll() as $row) {
				$person[] = new Person($row);
			}
			parent::disconnect($conn);
			return array($person);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}	}
		
	//this is a general function that people can use to get any enum value.
	public static function getEnumValues($colName) {
		$conn=parent::connect();
		$sql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . TBL_PERSON . "' AND COLUMN_NAME = :colName";
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
	
	//update the person's information. WE MUST HAVE A UNIQUE EMAIL ADDRESS FOR THE PERSON!	
	public function updatePerson($person_email) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_PERSON . " SET
				person_first_name = :person_first_name,
				person_last_name = :person_last_name,
				person_email = :person_email,
				person_department = :person_department,
				person_hourly_rate = :person_hourly_rate,
				person_perm_id = :person_perm_id,
				person_type = :person_type,
				person_logo_link = :person_logo_link
				WHERE person_email = :person_email";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":person_first_name", $this->data["person_first_name"], PDO::PARAM_STR);
				$st->bindValue(":person_last_name", $this->data["person_last_name"], PDO::PARAM_STR);
				$st->bindValue(":person_email", $this->data["person_email"], PDO::PARAM_STR);
				$st->bindValue(":person_department", $this->data["person_department"], PDO::PARAM_STR);
				$st->bindValue(":person_hourly_rate", $this->data["person_hourly_rate"], PDO::PARAM_INT);
				$st->bindValue(":person_perm_id", $this->data["person_perm_id"], PDO::PARAM_INT);
				$st->bindValue(":person_type", $this->data["person_type"], PDO::PARAM_INT);
				$st->bindValue(":person_logo_link", basename($this->data["person_logo_link"]), PDO::PARAM_STR);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on update: " . $e->getMessage() . " sql is " . $sql);
			}
	}
	
//update the person's hourly rate field.	
	public function updatePersonHourlyRate($person_id, $person_hourly_rate) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_PERSON . " SET
				person_hourly_rate = :person_hourly_rate
				WHERE person_id = :person_id";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
				$st->bindValue(":person_hourly_rate", $person_hourly_rate, PDO::PARAM_INT);
				$st->execute();	
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on update: " . $e->getMessage() . " sql is " . $sql);
			}
	}

	public static function getImage($person_email) {
		$conn=parent::connect();
		$sql = "SELECT person_logo_link FROM " . TBL_PERSON . " WHERE person_email = :person_email";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage() . " sql is " . $sql);
		}
	}
	
	public function setUserPassword($person_email, $person_password) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_PERSON . " SET 
		person_password = password(:person_password) 
		WHERE person_email = :person_email";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->bindValue(":person_password", $person_password, PDO::PARAM_STR);
			$st->execute();
			parent::disconnect($conn);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage() . " sql is " . $sql);
		}
	}
	
	//We may find this useful later, but probably not.
	public static function getPassword($person_email) {
		$conn=parent::connect();
		$sql = "SELECT person_password FROM " . TBL_PERSON . " WHERE person_email = :person_email";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage() . " sql is " . $sql);
		}
	}
	
	//get the person_id based on the email.
	public static function getPersonId($person_email) {
		$conn=parent::connect();
		$sql = "SELECT person_id FROM " . TBL_PERSON . " WHERE person_email = :person_email";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_email", $person_email, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return $row;
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage() . " sql is " . $sql);
		}
	}
	
	//delete this person, assumes they have no active projects.
	//first clear out their permissions. Then delete them.
	public function deletePerson($person_id) {
		$conn=parent::connect();
		$sql = "DELETE FROM " . TBL_PERSON_PERMISSIONS . " WHERE person_id = :person_id";
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();
			parent::disconnect($conn);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on delete of person permissions: " . $e->getMessage());
		}

		$conn=parent::connect();
		$sql = "DELETE FROM " . TBL_PERSON . " WHERE person_id = :person_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":person_id", $person_id, PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
			return 1;
		} catch (PDOException $e) {
			error_log("THERE WAS A PROBLEM HERE " . $e);
			parent::disconnect($conn);
			return 0;
			die("Query failed on delete of person: " . $e->getMessage());
		}
	}
}
	

?>