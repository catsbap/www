<?php
//queries for main report

class Project_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function display_projects() {
		$rows = array();		
		$query = $this->db->select('project.*');
		$query = $this->db->from('project');
		$this->db->where('project_archived', 0);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}
	
	function display_projects_and_clients() {
		$rows = array();		
		$query = $this->db->select('project.*');
		$query = $this->db->from('project');
		$query = $this->db->select('client.*');
		$query = $this->db->join('client', 'project.client_id = client.client_id');
		$this->db->where('project_archived', 0);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
	
	function get_project_id_by_name($project_name) {
		$rows = array();		
		$query = $this->db->select('project.project_id');
		$query = $this->db->from('project');
		$this->db->where('project_name =', $project_name);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}
	
	function display_project_by_id($project_id) {
		$rows = array();		
		$query = $this->db->select('project.*');
		$query = $this->db->from('project');
		$this->db->where('project_id =', $project_id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
		
	function insert_project() {
		$project_name = $this->input->post('project-name');
		$project_code = $this->input->post('project-code');
		$project_notes = $this->input->post('project-notes');
		$client_id = $this->input->post('client_ids');
		$project_billable = $this->input->post('project_billable');
		$project_invoice_by = $this->input->post('project_invoice_by');
		$project_hourly_rate = $this->input->post('project_hourly_rate');
		$project_budget_by = $this->input->post('project_budget_by');
		$project_budget_total_fees = $this->input->post('project_budget_total_fees');
		$project_budget_total_hours = $this->input->post('project_budget_total_hours');
		$project_send_email_percentage = $this->input->post('project_send_email_percentage');
		$project_show_budget = $this->input->post('project_show_budget');
		$project_budget_includes_expenses = $this->input->post('project_budget_includes_expenses');
		$project_archived = $this->input->post('project_archived');
		
		//error_log(print_r($data, true));

		$data = array(
			'project_name'=>$project_name,
			'project_code'=>$project_code,
			'project_notes'=>$project_notes,
			'client_id'=>$client_id,
			'project_billable'=>$project_billable,
			'project_invoice_by'=>$project_invoice_by,
			'project_hourly_rate'=>$project_hourly_rate,
			'project_budget_by'=>$project_budget_by,
			'project_budget_total_fees'=>$project_budget_total_fees,
			'project_budget_total_hours'=>$project_budget_total_hours,
			'project_send_email_percentage'=>$project_send_email_percentage,
			'project_show_budget'=>$project_show_budget,
			'project_budget_includes_expenses'=>$project_budget_includes_expenses,
			'project_archived'=>$project_archived,

		);
		error_log(print_r($data, true));
		$this->db->insert('project', $data);
	}
	
	function insert_project_person() {
		error_log(print_r($_POST, true));
		$project_name = $this->input->post('project-name');
		$person_id = $this->input->post('person_id');
		$project_assigned_by = $this->session->userdata( 'email' );
		
		$project_id = $this->get_project_id_by_name($project_name);
		$people = explode(',', $person_id);
		$people = array_unique($people);
		foreach ($people as $person) {
			if ($person) {
				$data = array(
					'project_id'=>$project_id[0]->project_id,
					'person_id'=>$person,
					'project_assigned_by'=>$project_assigned_by,
					'total_budget_hours'=>$this->input->post("$person" . "_hours_per_person"),
				);
				$this->db->insert('project_person', $data);
			}
		}
	}
	
	function insert_project_task() {
		error_log(print_r($_POST, true));
		$project_name = $this->input->post('project-name');
		$task_id = $this->input->post('task_id');
		
		$project_id = $this->get_project_id_by_name($project_name);

		$tasks = explode(',', $task_id);
		//update this so that the values inserted are unique.
		$tasks = array_unique($tasks);
		foreach ($tasks as $task) {
			if ($task) {
				$data = array(
					'project_id'=>$project_id[0]->project_id,
					'task_id'=>$task,
					'total_budget_hours'=>$this->input->post("$task" . "_hours_per_task"),
				);
				$this->db->insert('project_task', $data);
			}
		}
	}


	function update_project($project_id) {
		$project_name = $this->input->post('project-name');
		$project_code = $this->input->post('project-code');
		$project_notes = $this->input->post('project-notes');
		$client_id = $this->input->post('client_id');
		$project_billable = $this->input->post('project_billable');
		$project_invoice_by = $this->input->post('project_invoice_by');
		$project_hourly_rate = $this->input->post('project_hourly_rate');
		$project_budget_by = $this->input->post('project_budget_by');
		$project_budget_total_fees = $this->input->post('project_budget_total_fees');
		$project_budget_total_hours = $this->input->post('project_budget_total_hours');
		$project_send_email_percentage = $this->input->post('project_send_email_percentage');
		$project_show_budget = $this->input->post('project_show_budget');
		$project_budget_includes_expenses = $this->input->post('project_budget_includes_expenses');
		$project_archived = $this->input->post('project_archived');
		
		//error_log(print_r($data, true));

		$data = array(
			'project_name'=>$project_name,
			'project_code'=>$project_code,
			'project_notes'=>$project_notes,
			'client_id'=>$client_id,
			'project_billable'=>$project_billable,
			'project_invoice_by'=>$project_invoice_by,
			'project_hourly_rate'=>$project_hourly_rate,
			'project_budget_by'=>$project_budget_by,
			'project_budget_total_fees'=>$project_budget_total_fees,
			'project_budget_total_hours'=>$project_budget_total_hours,
			'project_send_email_percentage'=>$project_send_email_percentage,
			'project_show_budget'=>$project_show_budget,
			'project_budget_includes_expenses'=>$project_budget_includes_expenses,
			'project_archived'=>$project_archived,

		);
		error_log(print_r($data,true));
		$this->db->where('project_id', $project_id);
		$this->db->update('project', $data);
		//just put this out to the UI.
		echo "project " . $project_name . " updated successfully.";
	}

	function update_project_person($project_id) {
		error_log(print_r($_POST, true));
		$person_id = $this->input->post('person_id');
		$project_assigned_by = $this->session->userdata( 'email' );
		
		
		$people = explode(',', $person_id);
		//update this so that the values inserted are unique.
		$people = array_unique($people);
		$this->db->where('project_id', $project_id);
		$this->db->delete('project_person');
		foreach ($people as $person) {
			if ($person) {
				$data = array(
					'project_id'=>$project_id,
					'person_id'=>$person,
					'project_assigned_by'=>$project_assigned_by,
					'total_budget_hours'=>$this->input->post("$person" . "_hours_per_person"),
				);
				$this->db->where('project_id', $project_id);
				error_log(print_r($data,true));
				$this->db->insert('project_person', $data);
			}
		}
	}
	
	function update_project_task($project_id) {
		error_log(print_r($_POST, true));
		$task_id = $this->input->post('task_id');
		
		
		$tasks = explode(',', $task_id);
		//update this so that the values inserted are unique.
		$tasks = array_unique($tasks);
		$this->db->where('project_id', $project_id);
		$this->db->delete('project_task');
		foreach ($tasks as $task) {
			if ($task) {
				$data = array(
					'project_id'=>$project_id,
					'task_id'=>$task,
					'total_budget_hours'=>$this->input->post("$task" . "_hours_per_task"),
				);
				$this->db->where('project_id', $project_id);
				error_log(print_r($data,true));
				$this->db->insert('project_task', $data);
			}
		}
	}
	
	function update_task_hourly_rate() {
		$task_id = $this->input->post('task_id');
		$tasks = explode(',', $task_id);
		foreach ($tasks as $task) {
			if ($task) {
				$task_hourly_rate = $this->input->post("$task" . "_task_hourly_rate");
				$data = array(
					'task_hourly_rate'=>$task_hourly_rate,
				);
				$this->db->where('task_id', $task);
				$this->db->update('task', $data);
			}
		}
	}
	
	function update_person_hourly_rate() {
		$person_id = $this->input->post('person_id');
		$people = explode(',', $person_id);
		foreach ($people as $person) {
			if ($person) {
				$person_hourly_rate = $this->input->post("$person" . "_person_hourly_rate");
				$data = array(
					'person_hourly_rate'=>$person_hourly_rate,
				);
				$this->db->where('person_id', $person);
				$this->db->update('person', $data);
			}
		}
	}

}


