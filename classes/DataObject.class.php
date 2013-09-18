<?php

//objects that store and retrieve information from the DB

//only require the config file once in the app.

require_once("../config/config.php");

abstract class DataObject  {
	//the array can be used by objects but not directly accessed. This is the array that holds the individual objects created with their values retrieved from the database.
	protected $data = array();
	
	//Class's constructor. This is called whenever an object is created. This takes the array of field names and values and stores
	//them in the $data array.
	public function __construct($data) {
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data)) $this->data[$key] = $value;
		}
	}
	
	//function takes field name and then checks the $data array for the field. An error is generated if it is not found. 
	//if it is found, return the field.
	public function getValue($field) {
		if (array_key_exists($field, $this->data)) {
			return $this->data[$field];
		}else{
			die("field not found, field name is " . $field);
		}
	}
	
	//function to encode values. This helps with security and in generating xhml. THIS IS A CONVENIENCE FUNCTION.
	public function getValueEncoded($field) {
		return htmlspecialchars($this->getValue($field));
	}
	
	//connect function
	protected function connect() {
		try {
			$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			//keep this open.
			$conn->setAttribute(PDO::ATTR_PERSISTENT, true);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDO_EXCEPTION $e) {
			die("connection failed: " . $e->getMessage());
		}
		
		return $conn;
	}
	
	//disc funct, destroy the object.
	protected function disconnect($conn) {
		$conn="";
	}
}
?>