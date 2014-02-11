<?php
//queries for main report

class Client_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function getClientId($client_name) {
		$rows = array();
		$clientquery = $this->db->select('client.client_id');
		$clientquery = $this->db->from('client');
		$clientquery = $this->db->where('client.client_name =', $client_name);
		$clientquery = $this->db->get();	
		foreach($clientquery->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
	
	function display_clients() {
		$query = $this->db->select('*');
		$query = $this->db->from('client');
		$query = $this->db->get();	
		return $query->result();
	}
	
	function display_contacts_by_client_id ($client_id) {
		$rows = array();
		$query = $this->db->select('*');
		$query = $this->db->from('contact');
		$query = $this->db->where('contact.client_id =', $client_id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
	function display_clients_by_id($client_id) {
		$query = $this->db->select('client.*');
		$query = $this->db->from('client');
		$query = $this->db->select('client_address.*');
		$query = $this->db->join('client_address', 'client.client_id = client_address.client_id');
		$query = $this->db->where('client.client_id =', $client_id);
		$query = $this->db->get();	
		return $query->result();
	}
	
	function insert_client() {
		$client_name = $this->input->post('client-name');
		$client_phone = $this->input->post('client-phone');
		$client_email = $this->input->post('client-email');
		$client_fax = $this->input->post('client-fax');
		$client_currency_index = $this->input->post('client_currency_index');
		$client_logo_link = $this->input->post('client_logo_link');
		$client_archived = $this->input->post('client-archived');
		$client_address = $this->input->post('client-streetAddress');
		$client_state =$this->input->post('client-state');
		$client_zip = $this->input->post('client-zip');
		$client_city = $this->input->post('client-city');
		$contact_name = $this->input->post('contact-name');
		$client_city = $this->input->post('contact-title');
		$contact_primary = $this->input->post('contact-primary');
		if (!$contact_primary) {
			$contact_primary = 0;
		}
		$contact_officePhone = $this->input->post('contact-officePhone'); 
		$contact_mobilePhone = $this->input->post('contact-mobilePhone'); 
		$contact_email = $this->input->post('contact-email');
		$contact_fax = $this->input->post('contact-fax');
		$data = array(
			'client_name'=>$client_name,
			'client_phone'=>$client_phone,
			'client_email'=>$client_email,
			'client_fax'=>$client_fax,
			'client_currency_index'=>$client_currency_index,
			'client_logo_link'=>$client_logo_link,
			'client_archived'=>$client_archived
		);
		error_log(print_r($data, true));
		$this->db->insert('client', $data);
		$id = $this->db->insert_id();		
		error_log("ID IS " . $id);
		$data = array(
			'client_id' =>$id,
			'client_address' =>$client_address,
			'client_state' => $client_state,
			'client_zip' => $client_zip,
			'client_city' => $client_city
		);
		$this->db->insert('client_address', $data);
		error_log(print_r($data, true));
		$data = array(
			'client_id' =>$id,
			'contact_name' =>$contact_name,
			'contact_primary' => $contact_primary,
			'contact_office_number' => $contact_officePhone,
			'contact_mobile_number' => $contact_mobilePhone,
			'contact_fax_number' => $contact_fax
		);
		$this->db->insert('contact', $data);
		error_log(print_r($data, true));
	}
	
	function update_client($client_id) {
		$client_name = $this->input->post('client-name');
		$client_phone = $this->input->post('client-phone');
		$client_email = $this->input->post('client-email');
		$client_fax = $this->input->post('client-fax');
		$client_currency_index = $this->input->post('client_currency_index');
		$client_logo_link = $this->input->post('client_logo_link');
		$client_archived = $this->input->post('client-archived');
		$client_address = $this->input->post('client-streetAddress');
		$client_state =$this->input->post('client-state');
		$client_zip = $this->input->post('client-zip');
		$client_city = $this->input->post('client-city');
		$contact_name = $this->input->post('contact-name');
		$client_city = $this->input->post('contact-title');
		$contact_primary = $this->input->post('contact-primary');
		if (!$contact_primary) {
			$contact_primary = 0;
		}
		$contact_officePhone = $this->input->post('contact-officePhone'); 
		$contact_mobilePhone = $this->input->post('contact-mobilePhone'); 
		$contact_email = $this->input->post('contact-email');
		$contact_fax = $this->input->post('contact-fax');
		$contact_id = $this->input->post('contact-id');
		$data = array(
			'client_name'=>$client_name,
			'client_phone'=>$client_phone,
			'client_email'=>$client_email,
			'client_fax'=>$client_fax,
			'client_currency_index'=>$client_currency_index,
			'client_logo_link'=>$client_logo_link,
			'client_archived'=>$client_archived
		);
		//error_log(print_r($data, true));
		$this->db->where('client_id', $client_id);
		$this->db->update('client', $data);
		$data = array(
			'client_address' =>$client_address,
			'client_state' => $client_state,
			'client_zip' => $client_zip,
			'client_city' => $client_city
		);
		$this->db->where('client_id', $client_id);
		$this->db->update('client_address', $data);
		//error_log(print_r($data, true));
		//update each contact here. 
		//contacts are added and removed in the UI first,
		//and then added here as one group from those remaining
		//as active in the UI.
		$this->db->where('client_id', $client_id);
		$this->db->delete('contact');
		$num_items = (count($contact_id));
		for ($i=0; $i<$num_items; $i++) {
			$data = array(
				'client_id' => $client_id,
				'contact_id' => $contact_id[$i],
				'contact_name' =>$contact_name[$i],
				'contact_primary' => $contact_primary[$i],
				'contact_office_number' => $contact_officePhone[$i],
				'contact_mobile_number' => $contact_mobilePhone[$i],
				'contact_fax_number' => $contact_fax[$i]
			);
				$this->db->insert('contact', $data);
				//error_log(print_r($data, true));
		}
	}
	
	function delete_client($client_id) {
		//stub
	}
	
	function delete_contact($contact_id) {
		//$this->db->delete('contact', array('contact_id' => $contact_id)); 
	}

}


