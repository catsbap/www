<?php


//connectivity
require_once("DataObject.class.php");

class Client extends DataObject {
	protected $data = array(
		"client_id"=>"",
		"client_name"=>"",
		"client_address"=>"",
		"client_currency_index"=>"",
		"client_logo_link"=>"",
		"client_email"=>"",
		"client_phone"=>"",
		//address fields, need to use for detailed addy.
		//"client_address_number"=>"",
		//"client_street_name"=>"",
		"client_state"=>"",
		"client_zip"=>"",
		//"client_apartment"=>"",
		"client_fax"=>"",
		"client_city"=>""
	);
	
	//display all information about a client returned as an array
	public static function getClients() {
		$conn=parent::connect();
		$sql="SELECT * FROM " . TBL_CLIENT;
		try {
			$st = $conn->prepare($sql);
			$st->execute();
			$client=array();
			foreach ($st->fetchAll() as $row) {
				$clients[] = new Client($row);
			}
			$row=$st->fetch();
			parent::disconnect($conn);
			return array($clients);
		}catch(PDOException $e) {
			parent::disconnect($conn);
			die("query failed here: " . $e->getMessage() . "query is " . $sql);
		}
	}
	
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
	*/
	
	
	//return all data for a specific client based on the client_id.
	public static function getClient($clientId) {
		$conn=parent::connect();
		//OLD SQL, use a left join here, rather than just deliver an error for missing data.
		//$sql = "SELECT " . TBL_CLIENT . ".*," . TBL_CLIENT_ADDRESS . ".* FROM " . TBL_CLIENT . "," . TBL_CLIENT_ADDRESS . " WHERE " . TBL_CLIENT . ".client_id = :client_id and ". TBL_CLIENT . ".client_id=" . TBL_CLIENT_ADDRESS . ".client_id";
		$sql = "SELECT " . TBL_CLIENT . ".*," . TBL_CLIENT_ADDRESS . ".* FROM " . TBL_CLIENT . " as client LEFT JOIN " . TBL_CLIENT_ADDRESS . " as client_address on client.client_id = client_address.client_id WHERE client.client_id = :client_id";
		try {
			$st = $conn->prepare($sql);
			$st->bindValue(":client_id", $clientId, PDO::PARAM_INT);
			$st->execute();
			$row=$st->fetch();
			parent::disconnect($conn);
			if ($row) return new Client($row);
		} catch(PDOException $e) {
			parent::disconnect($conn);
			die("Query failed on you: " . $e->getMessage());
		}
	}
	
	
	//return the clients name based on the client_id.
	//I'll keep this here as a utility function.
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
	
	//function inserts new client into db. If ADDRESS_CONFIG value is 0, insert the address as a large varchar field, not as individual fields in the database.
	//it also gets the key for the record being inserted and inserts
	//a row in the address table if the information was sent.
	
	public function insertClient($client_email) {
		//FORGET THE CONFIG!
		//if (ADDRESS_CONFIG == 1) {	
		//insert the client into the client table. Insert the address components into the client_address table..
			$conn=parent::connect();
			$sql = "INSERT INTO " . TBL_CLIENT . " (
				client_name,
				client_email,
				client_phone,
				client_fax,
				client_currency_index
				) VALUES (
				:client_name,
				:client_email,
				:client_phone,
				:client_fax,
				:client_currency_index
				)";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":client_name", $this->data["client_name"], PDO::PARAM_STR);
				$st->bindValue(":client_email", $this->data["client_email"], PDO::PARAM_STR);
				$st->bindValue(":client_phone", $this->data["client_phone"], PDO::PARAM_INT);
				$st->bindValue(":client_fax", $this->data["client_fax"], PDO::PARAM_INT);
				$st->bindValue(":client_currency_index", $this->data["client_currency_index"], PDO::PARAM_INT);
				$st->execute();
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on insert, sql is $sql " . $e->getMessage());
			}	
			//get the client ID out of the client table based on the email address we just inserted. It must use the same key (auto increment) created when the record
			//was inserted into the client table.
			$conn=parent::connect();
			$sql = "SELECT client_id FROM " . TBL_CLIENT . " WHERE client_email = '" . $client_email . "'";			
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":client_email", $client_email, PDO::PARAM_STR);
				$st->execute();
				$client_id=$st->fetch();
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed getting the client id, sql is $sql " . $e->getMessage());
			}
			//insert the address data into the address table for the appropriate client_id.
			$conn=parent::connect();
			$sql = "INSERT INTO " . TBL_CLIENT_ADDRESS . " (
				client_id,
				client_address,
				client_state,
				client_zip,
				client_city
			) VALUES (
				:client_id,
				:client_address,
				:client_state,
				:client_zip,
				:client_city
			)";
			
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":client_id", $client_id["client_id"], PDO::PARAM_STR);
				$st->bindValue(":client_address", $this->data["client_address"], PDO::PARAM_INT);
				$st->bindValue(":client_state", $this->data["client_state"], PDO::PARAM_STR);
				$st->bindValue(":client_zip", $this->data["client_zip"], PDO::PARAM_INT);
				$st->bindValue(":client_city", $this->data["client_city"], PDO::PARAM_STR);
				$st->execute();
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on insert of client address, sql is $sql " . $e->getMessage());
			}
		/*} else {
			$conn=parent::connect();
			$sql = "INSERT INTO " . TBL_CLIENT . " (
				client_name,
				client_address,
				client_currency_index
				) VALUES (
				:client_name,
				:client_address,
				:client_currency_index
				)";
			try {
				$st = $conn->prepare($sql);
				$st->bindValue(":client_name", $this->data["client_name"], PDO::PARAM_STR);
				$st->bindValue(":client_address", $this->data["client_address"], PDO::PARAM_STR);
				$st->bindValue(":client_currency_index", $this->data["client_currency_index"], PDO::PARAM_INT);
				$st->execute();
				parent::disconnect($conn);
			} catch (PDOException $e) {
				parent::disconnect($conn);
				die("Query failed on insert of new client record, sql is $sql " . $e->getMessage());
			}	
		}*/
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
	
/*	// OLD function stubs below this line.
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