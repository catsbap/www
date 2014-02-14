<?php
//queries for main report

class Person_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function display_projects() {
		$rows = array();
		$query = $this->db->select('project.*');
		$query = $this->db->from('project');
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
	
	function display_people() {
		$rows = array();
		$query = $this->db->select('person.*');
		$query = $this->db->from('person');
		$query = $this->db->select('person_permissions.*');
		$query = $this->db->join('person_permissions', 'person.person_id = person_permissions.person_id');
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
	function display_person_perms() {
		$rows = array();
		$query = $this->db->distinct('person_perm_id');
		$query = $this->db->from('person_permissions');	
		$query = $this->db->group_by('person_perm_id');
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
		function display_person_types() {
		$rows = array();
		$query = $this->db->distinct('person_type');
		$query = $this->db->from('person');	
		$query = $this->db->group_by('person_type');
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
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
	
	function display_people_by_id($person_id) {
		$query = $this->db->select('person.*');
		$query = $this->db->from('person');
		$query = $this->db->select('person_permissions.*');
		$query = $this->db->join('person_permissions', 'person.person_id = person_permissions.person_id');
		$query = $this->db->where('person.person_id =', $person_id);
		$query = $this->db->get();	
		return $query->result();
	}
	
	function insert_person() {
		$person_first_name = $this->input->post('person-first-name');
		$person_last_name = $this->input->post('person-last-name');
		$person_email = $this->input->post('person-email');
		$person_department = $this->input->post('person-department');
		$person_hourly_rate = $this->input->post('person-hourly-rate');
		$person_perms = $this->input->post('person-perms');
		$person_type = $this->input->post('person-type');
		$data = array(
			'person_first_name'=>$person_first_name,
			'person_last_name'=>$person_last_name,
			'person_email'=>$person_email,
			'person_department'=>$person_department,
			'person_hourly_rate'=>$person_hourly_rate,
			'person_perm_id'=>$person_perms,
			'person_type'=>$person_type,
		);
		error_log(print_r($data, true));
		$this->db->insert('person', $data);
		$id = $this->db->insert_id();		
		error_log("ID IS " . $id);
		$create_projects = "";
		$view_rates = "";
		$create_invoices = "";
		if ($person_perms == "Administrator") {
			$create_projects = "1";
			$view_rates = "1";
			$create_invoices = "1";
		} else if ($person_perms == "Regular User") {
			$create_projects = "0";
			$view_rates = "0";
			$create_invoices = "0";
		} else if ($person_perms == "Project Manager") {
			$create_projects = 0;
			if ($this->input->post('create_projects') == "on") {
				$create_projects = 1;
			}	
			$view_rates = 0;
			if ($this->input->post('view_rates') == "on") {
				$view_rates = 1;
			}
			$create_invoices = 0;
			if ($this->input->post('create_invoices') == "on") {
				$create_invoices = 1;
			}
		}
		$data = array(
			'person_id' =>$id,
			'person_perm_id' =>$person_perms,
			'create_projects' => $create_projects,
			'view_rates' => $view_rates,
			'create_invoices' => $create_invoices
		);
		$this->db->insert('person_permissions', $data);
		error_log(print_r($data, true));
		
	}
	
	function display_projects_for_person($person_id) {
		//$sql = "SELECT * FROM " . TBL_PROJECT . " WHERE project_id IN (SELECT project_id FROM " . TBL_PROJECT_PERSON . " WHERE person_id = :person_id)";

		$rows = array();
		$query = $this->db->select('project.*');
		$query = $this->db->from('project');
		$query = $this->db->join('project_person', 'project.project_id = project_person.project_id');
		$query = $this->db->where('project_person.person_id =', $person_id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;

	}
	

	function update_person($person_id) {
		$person_first_name = $this->input->post('person-first-name');
		$person_last_name = $this->input->post('person-last-name');
		$person_email = $this->input->post('person-email');
		$person_department = $this->input->post('person-department');
		$person_hourly_rate = $this->input->post('person-hourly-rate');
		$person_perms = $this->input->post('person-perms');
		$person_type = $this->input->post('person-type');
		$data = array(
			'person_first_name'=>$person_first_name,
			'person_last_name'=>$person_last_name,
			'person_email'=>$person_email,
			'person_department'=>$person_department,
			'person_hourly_rate'=>$person_hourly_rate,
			'person_perm_id'=>$person_perms,
			'person_type'=>$person_type,
		);
		error_log(print_r($data, true));
		$this->db->where('person_id', $person_id);
		$this->db->update('person', $data);
		$create_projects = "";
		$view_rates = "";
		$create_invoices = "";
		if ($person_perms == "Administrator") {
			$create_projects = "1";
			$view_rates = "1";
			$create_invoices = "1";
		} else if ($person_perms == "Regular User") {
			$create_projects = "0";
			$view_rates = "0";
			$create_invoices = "0";
		} else if ($person_perms == "Project Manager") {
			$create_projects = 0;
			if ($this->input->post('create_projects') == "on") {
				$create_projects = 1;
			}	
			$view_rates = 0;
			if ($this->input->post('view_rates') == "on") {
				$view_rates = 1;
			}
			$create_invoices = 0;
			if ($this->input->post('create_invoices') == "on") {
				$create_invoices = 1;
			}
		}
		$data = array(
			'person_id' =>$person_id,
			'person_perm_id' =>$person_perms,
			'create_projects' => $create_projects,
			'view_rates' => $view_rates,
			'create_invoices' => $create_invoices
		);
		$this->db->where('person_id', $person_id);
		$this->db->update('person_permissions', $data);		error_log(print_r($data, true));

	}
	
	function delete_client($client_id) {
		//stub
	}
	
	function delete_contact($contact_id) {
		//$this->db->delete('contact', array('contact_id' => $contact_id)); 
	}

}


