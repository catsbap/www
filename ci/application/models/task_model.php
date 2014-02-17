<?php
//queries for main report

class Task_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function display_tasks() {
		$rows = array();		
		$query = $this->db->select('task.*');
		$query = $this->db->from('task');
		$this->db->where('task_archived', 0);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
	
	function display_archived_tasks() {
		$rows = array();		
		$query = $this->db->select('task.*');
		$query = $this->db->from('task');
		$this->db->where('task_archived', 1);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
		
	function insert_task() {
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
		$this->db->insert('task', $data);
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


