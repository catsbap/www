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

	function update_task($task_id) {
		$data['task_id'] = $this->uri->segment(3);
		$task_name = $this->input->post('task_name');
		$task_hourly_rate = $this->input->post('task_hourly_rate');
		$task_bill_by_default = $this->input->post('task_bill_by_default');
		$task_common = $this->input->post('task_common');
		$task_archived = $this->input->post('task_archived');
		//error_log(print_r($data, true));

		$data = array(
			'task_name'=>$task_name,
			'task_hourly_rate'=>$task_hourly_rate,
			'task_bill_by_default'=>$task_bill_by_default,
			'task_common'=>$task_common,
			'task_archived'=>$task_archived,
		);
		error_log(print_r($data, true));
		$this->db->where('task_id', $task_id);
		$this->db->update('task', $data);
		echo "task " . $task_name . " updated successfully.";
	}

}


