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
	
	function display_person_perms_by_id($person_id) {
		$rows = array();
		$query = $this->db->select('*');
		$query = $this->db->from('person_permissions');	
		$query = $this->db->where('person_id =', $person_id);
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
		
		//first, update ion auth with the user's email address as their login. 
		//there are only three arguments required by ion auth, so we are using email for two of them.
		$username = $person_email;
		//password is set to default value when the user is first set up.
		//set up the user's permission level here to be consistent with ion auth.
		//in the future, we should update the application
		//so that it's not using the string from the UI, but rather a number 1=admin, 2=pm, 3=regular user
		if ($person_perms == "Administrator") {
			$ion_auth_level = "1";
		} elseif ($person_perms = "Project Manager") {
			$ion_auth_level = "3";
		} else {
			$ion_auth_level = "2";
		}
		$password = "12345";
		$email = $person_email;
		$additional_data = array(
			'first_name' => $person_first_name,
			'last_name' => $person_last_name,
		);
		//insert record into the auth table with the user's email, but blank password. they will set it later when they receive the invitation email.
		$id = "";

		if (!$this->ion_auth->register($username, $password, $email, $additional_data, array($ion_auth_level)) == 0) {
			//it appears that ion_auth updates the user_groups table last, so last_insert id is the ID from that table, not the user_id,
			//so we can't use it here. Instead, get the id out of the db based on the user's email, since that is a unique value.
			//$id = $this->db->insert_id();	
			$query = $this->db->select('id');
			$query = $this->db->from('users');
			$query = $this->db->where('email =', $email);
			$q = $this->db->get();	
			$dbdata = array_shift($q->result_array());
			$id = $dbdata['id'];
			//error_log("here is id " . $id);	
			//error_log("successfully inserted " . $person_email);
			//error_log($email);
			//error_log($username);
			//error_log(print_r($additional_data,true));
			//use the insert id to put the user into the person's table.
			$data = array(
				'person_id' => $id,
				'person_first_name'=>$person_first_name,
				'person_last_name'=>$person_last_name,
				'person_email'=>$person_email,
				'person_department'=>$person_department,
				'person_hourly_rate'=>$person_hourly_rate,
				'person_perm_id'=>$person_perms,
				'person_type'=>$person_type,
			);
			//error_log(print_r($data, true));
			//error_log("create invoices is " . $this->input->post('create_invoices'));
			$this->db->insert('person', $data);
			$create_projects = "0";
			$view_rates = "0";
			$create_invoices = "0";
			if ($person_perms == "Administrator") {
				$create_projects = "1";
				$view_rates = "1";
				$create_invoices = "1";
			} else if ($person_perms == "Regular User") {
				$create_projects = "0";
				$view_rates = "0";
				$create_invoices = "0";
			} else if ($person_perms == "Project Manager") {
				if ($this->input->post('create_projects') == "on") {
					$create_projects = 1;
				}	
				if ($this->input->post('view_rates') == "on") {
					$view_rates = 1;
				}
				if ($this->input->post('create_invoices') == "on") {
					$create_invoices = 1;
				}
			}
			//update the permissions table with data submitted in the form.
			$data = array(
				'person_id' =>$id,
				'person_perm_id' =>$person_perms,
				'create_projects' => $create_projects,
				'view_rates' => $view_rates,
				'create_invoices' => $create_invoices
			);
			$this->db->insert('person_permissions', $data);
			//error_log(print_r($data, true));
		}
	}
	
	function display_projects_for_person($person_id) {
		//$sql = "SELECT * FROM " . TBL_PROJECT . " WHERE project_id IN (SELECT project_id FROM " . TBL_PROJECT_PERSON . " WHERE person_id = :person_id)";
		//error_log("Person id is " . $person_id);
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
	
	function set_user_password() {
		$id = $this->input->post('login');
		$old = "12345";
		$new = $this->input->post('password1');
		//error_log("got here " . $new . " and " . $id . " and " . $old);
		if ($this->ion_auth->change_password($id, $old, $new) == 0) {
			return 0;
		} else {
			return 1;
		}
		//update the password here
	}
	

	function update_person($person_id) {
		$person_first_name = $this->input->post('person-first-name');
		$person_last_name = $this->input->post('person-last-name');
		$person_email = $this->input->post('person-email');
		$person_department = $this->input->post('person-department');
		$person_hourly_rate = $this->input->post('person-hourly-rate');
		$person_perms = $this->input->post('person-perms');
		$person_type = $this->input->post('person-type');
		$person_add_btn = $this->input->post('person-add-btn');
		if ($person_add_btn == "Save Person") {
			//update the person's information
			$data = array(
				'person_first_name'=>$person_first_name,
				'person_last_name'=>$person_last_name,
				'person_email'=>$person_email,
				'person_department'=>$person_department,
				'person_hourly_rate'=>$person_hourly_rate,
				'person_perm_id'=>$person_perms,
				'person_type'=>$person_type,
			);
			$this->db->where('person_id', $person_id);
			$this->db->update('person', $data);
			$create_projects = "0";
			$view_rates = "0";
			$create_invoices = "0";
			//initialize auth level to 2, regular user.
			$ion_auth_level = "2";
			if ($person_perms == "Administrator") {
				$create_projects = "1";
				$view_rates = "1";
				$create_invoices = "1";
				$ion_auth_level = "1";
			} else if ($person_perms == "Regular User") {
				$create_projects = "0";
				$view_rates = "0";
				$create_invoices = "0";
				$ion_auth_level = "2";
			} else if ($person_perms == "Project Manager") {
				if ($this->input->post('create_projects') == "on") {
					$create_projects = 1;
				}	
				if ($this->input->post('view_rates') == "on") {
					$view_rates = 1;
				}
				if ($this->input->post('create_invoices') == "on") {
					$create_invoices = 1;
				}
				$ion_auth_level = "3";
			}	
			//update the person's permission level
			$data = array(
				'person_id' =>$person_id,
				'person_perm_id' =>$person_perms,
				'create_projects' => $create_projects,
				'view_rates' => $view_rates,
				'create_invoices' => $create_invoices
			);
			$this->db->where('person_id', $person_id);
			$this->db->update('person_permissions', $data);		
			//error_log(print_r($data, true));
			//and update ion_auth (to change their user group)
			//this is a bug fix that takes care of ion_auth admin user id (1) being different from teddy administrator (198).
			//this should not happen except during debugging.
			if ($person_id == 198) {
				$ion_auth_person_id = 1;
			} else {
				$ion_auth_person_id = $person_id;
			}
			$this->ion_auth->remove_from_group(NULL, $ion_auth_person_id);
			$this->ion_auth->add_to_group($ion_auth_level, $ion_auth_person_id );
		} elseif ($person_add_btn == "Save Projects") {
			//update the user's projects based on the values in the input field
			$projects = explode(",", $this->input->post("projectidselectname"));
			$projects = array_unique($projects);
			//delete all assigned project records first, then add the ones the administrator assigned.
			$this->db->delete('project_person', array('person_id' => $person_id)); 
			
			foreach ($projects as $project) {
				//get total hours out of the project table
				$query = $this->db->select('project_budget_total_hours');
				$query = $this->db->where('project_id', $project);
				$q = $this->db->get('project');
				$dbdata = array_shift($q->result_array());
				$project_budget_total_hours = $dbdata['project_budget_total_hours'];
				//get the assigned by value out of the person table
				$query = $this->db->select('person_email');
				$query = $this->db->from('person');
				$query = $this->db->where('person_id =', $this->ion_auth->get_user_id());
				$q = $this->db->get();	
				$dbdata = array_shift($q->result_array());
				$project_assigned_by = $dbdata['person_email'];
				//this is a bug fix that takes care of ion_auth admin user id (1) being different from teddy administrator (198).
				//this should not happen except during debugging.
				if ($this->ion_auth->get_user_id() == 1) {
					$project_assigned_by = "admin@admin.com";
				}
				if ($project) {
					$data = array( 
						'project_id' =>$project,
						'person_id' =>$person_id,
						'project_assigned_by' => $project_assigned_by,
						'total_budget_hours' => $project_budget_total_hours,
					);
					//error_log(print_r($data),true);
					$this->db->insert('project_person', $data);
				}
			}
		} elseif ($person_add_btn == "Change Password") {
			$query = $this->db->select('person_email');
			$query = $this->db->from('person');
			$query = $this->db->where('person_id =', $person_id);
			$q = $this->db->get();	
			$dbdata = array_shift($q->result_array());
			$id = $dbdata['person_email'];			
			//$old = "12345";
			$old = $this->input->post('passwordOld');
			$new = $this->input->post('password1');
			//error_log("got here " . $new . " and " . $id . " and " . $old);
			if ($this->ion_auth->change_password($id, $old, $new) == 0) {
				return 0;
			} else {
				return 1;
			}
		} elseif ($person_add_btn == "Resend Invitation") {
				$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => 'testtrackmb@gmail.com',
				'smtp_pass' => 'mediabarn',
				'mailtype' => 'html',
				'charset'   => 'iso-8859-1',
				'starttls'  => true,
				'validate' => false,
				);
				
				//send the user an email with a link inviting them to update their password.
				//the password is originally set to "12345" when the person is invited to register.
				$this->email->initialize($config);
				$this->load->library('email');
				$this->email->set_newline("\r\n");

				$this->email->from('testtrackmb@gmail.com', 'admin@timetracker.com');
				//change this to use the email entered by the user.
				$this->email->to('catsbap@gmail.com');
				$this->email->subject('Email Test');
				$key = urlencode($person_email);
				$this->email->message('Click here to set up your Time Tracker account: ' . base_url()."index.php/reset_now_controller/index/" . $key);
		
				
				if($this->email->send())	{
		   			$data['success_email'] = "An email has been sent to this person with instructions for how to log in.";
		   		} else{
					show_error($this->email->print_debugger());
				}		   

		}
	}
	
	function delete_client($client_id) {
		//stub
	}
	
	function delete_contact($contact_id) {
		//$this->db->delete('contact', array('contact_id' => $contact_id)); 
	}

}


