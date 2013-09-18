<?php


require_once("DataObject.class.php");

class Contact extends DataObject {
	protected $data = array(
		"contact_id"=>"",
		"contact_first_name"=>"",
		"contact_last_name"=>"",
		"contact_title"=>"",
		//address fields
		"contact_email"=>"",
		"contact_office_number"=>"",
		"contact_mobile_number"=>"",
		"contact_fax_number"=>"",
		"contact_id"=>"",
		"client_id"=>"",
		"primary_contact"=>""
	);
	
	//display all contact data for the client
	public static function getContacts($client_id) {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_CONTACT . " where client_id = " . $client_id;
		
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			$contact=array();
			foreach ($st->fetchAll() as $row) {
				$contact[] = new Contact($row);
			}
			$row=$st->fetch();
			parent::disconnect($conn);
			return array($contact);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}

	//the information for the primary contact only
	public static function getPrimaryContact($client_id) {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_CONTACT . " where client_id = :client_id AND contact_primary = " . 1;
		
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Contact($row);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
		
	
/* 9/15	
	//return the data for a specific contact based on the client_id.
	public static function getClient($clientId) {
		$conn=parent::connect();
		//use a placeholder for the ID
		$sql = "SELECT * FROM " . TBL_CLIENT . " WHERE client_id = :client_id";
		
		try {
			//get the PDO object
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $clientId, PDO::PARAM_INT);
			$st->execute();
			//this is a small return, so fetch() works fine.
			$row=$st->fetch();
			parent::disconnect($conn);
			//send the client object back to the calling function.
			if ($row) return new Client($row);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	//return the client_id based on the client_name
	public function getClientId($client_name) {
		$conn=parent::connect();
		$sql = "SELECT client_id FROM " . TBL_CLIENT . " WHERE client_name = '" . $client_name . "'";			
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_name", $client_name, PDO::PARAM_STR);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Client($row);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed getting the client id, sql is $sql " . $e->getMessage());
		}
	}
	
	//get the available currencies out of the currency table
	//9/4: Client only needs US here
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
	
	//function inserts new client into db. If ADDRESS_CONFIG value is 0, insert the address as a large varchar field, not as individual fields in the database.
	//it also gets the key for the record being inserted and inserts
	//a row in the address table if the information was sent.
	
	public function insertContact($client_id) {
		$conn=parent::connect();
		$sql = "INSERT INTO " . TBL_CONTACT . " (
			contact_first_name,
			contact_last_name,
			contact_title,
			contact_email,
			contact_office_number,
			contact_mobile_number,
			contact_fax_number,
			client_id
			) VALUES (
			:contact_first_name,
			:contact_last_name,
			:contact_title,
			:contact_email,
			:contact_office_number,
			:contact_mobile_number,
			:contact_fax_number,
			:client_id
			)";
		
		try {
				$st = $conn->prepare($sql);
				$st->bindValue(":contact_first_name", $this->data["contact_first_name"], PDO::PARAM_STR);
				$st->bindValue(":contact_last_name", $this->data["contact_last_name"], PDO::PARAM_STR);
				$st->bindValue(":contact_title", $this->data["contact_title"], PDO::PARAM_STR);
				$st->bindValue(":contact_email", $this->data["contact_email"], PDO::PARAM_STR);
				$st->bindValue(":contact_office_number", $this->data["contact_office_number"], PDO::PARAM_INT);
				$st->bindValue(":contact_mobile_number", $this->data["contact_mobile_number"], PDO::PARAM_STR);
				$st->bindValue(":contact_fax_number", $this->data["contact_fax_number"], PDO::PARAM_STR);
				$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
				$st->execute();
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on insert of contact, sql is $sql " . $e->getMessage());
			}	
			
		}
	
	//update the client record based on the client_id
	//if we want to break out the address, write the config to do the update later
	//so that we can update those fields as well.
	//9/4/13
	public function updateClient($client_id) {
		$conn=parent::connect();
		$sql = "UPDATE " . TBL_CLIENT . " SET
			client_name = :client_name,
			client_address = :client_address,
			client_currency_index = :client_currency_index
			WHERE client_id = :client_id";
	try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $client_id, PDO::PARAM_INT);
			$st->bindValue(":client_name", $this->data["client_name"], PDO::PARAM_STR);
			$st->bindValue(":client_address", $this->data["client_address"], PDO::PARAM_STR);
			$st->bindValue(":client_currency_index", $this->data["client_currency_index"], PDO::PARAM_INT);
			$st->execute();	
			parent::disconnect($conn);
		} catch (PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on update: " . $e->getMessage());
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
}
?>