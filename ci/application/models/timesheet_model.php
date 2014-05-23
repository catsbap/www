<?php
//queries for main report

class Timesheet_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function display_timesheet($id, $collection, $startdate, $enddate) {
		$rows = array();		
		$query = $this->db->select('timesheet.*');
		$query = $this->db->from('timesheet');
		$this->db->where('person_id', $id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}	
	
	function getTimesheetById($person_id, $timesheet_start_date, $timesheet_end_date) {
		$rows = array();		
		$query = $this->db->select('timesheet.*');
		$query = $this->db->from('timesheet');
		$this->db->where('person_id', $person_id);
		$this->db->where('timesheet_start_date', $timesheet_start_date);
		$this->db->where('timesheet_end_date', $timesheet_end_date);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}
	
	
	function insertTimesheet($person_id, $timesheet_start_date, $timesheet_end_date) {
		$data = array(
			'timesheet_approved'=>'0',
			'timesheet_submitted'=>'0',
			'timesheet_start_date'=>$timesheet_start_date,
			'timesheet_end_date'=>$timesheet_end_date,
			'person_id'=>$person_id,
		);
		//RETURNING DATA TO THE CALLING FUNCTION SO THAT IT CAN BE USED IN DATA CONTROLLER.
		
		$this->db->insert('timesheet', $data);
		$timesheet_id = $this->db->insert_id();
		$data['timesheet_id'] = $timesheet_id;
		//return an array with the object here so we can get the properties in the controller.
		//$object = json_decode(json_encode($data), FALSE);
		$object = new stdClass();
		
		foreach ($data as $key => $value)
		{
			$object->$key = $value;
		}
		
		$array = array($object);
		return $array;
	}
	
	function getTimesheetItems($timesheet_item_id) {
		$rows = array();		
		$query = $this->db->select('timesheet_item.*');
		$query = $this->db->from('timesheet_item');
		$this->db->where('timesheet_item_id', $timesheet_item_id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		return $rows;
	}
	
	function getTimesheetItemForPersonProjectTask($person_id, $project_id, $task_id, $timesheet_start_date, $timesheet_end_date) {
		$rows = array();		
		$query = $this->db->select('timesheet_item.*');
		$query = $this->db->from('timesheet_item');
		$this->db->where('person_id', $person_id);
		$this->db->where('project_id', $project_id);
		$this->db->where('timesheet_start_date', $timesheet_start_date);
		$this->db->where('timesheet_end_date', $timesheet_end_date);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		if (count($rows) > 0) {
			return $rows;
		} else {
			return 0;
		}
	}
	
	function getTimesheetItemForDatePersonProjectTask($timesheet_date, $person_id, $project_id, $task_id) {
		$rows = array();		
		$query = $this->db->select('timesheet_item.*');
		$query = $this->db->from('timesheet_item');
		$this->db->where('timesheet_date', $timesheet_date);
		$this->db->where('person_id', $person_id);
		$this->db->where('project_id', $project_id);
		$this->db->where('task_id', $task_id);
		$query = $this->db->get();	
		foreach($query->result() as $row)
		{
		$rows[] = $row;
		}
		if (count($rows) > 0) {
			return $rows;
		} else {
			return 0;
		}
	}

	//function inserts new timesheet item into db. 	
	public function insertTimesheetItem($obj, $timesheet_item_id) {
		$data = array(
			'timesheet_item_id'=>$timesheet_item_id,
			'person_id'=>$obj->person_id,
			'timesheet_date'=>$obj->timesheet_date,
			'task_id'=>$obj->task_id,
			'project_id'=>$obj->project_id,
			'timesheet_hours'=>$obj->timesheet_hours,
			'timesheet_notes'=>$obj->timesheet_notes
		);
		$this->db->insert('timesheet_item', $data);
	}


	public function updateTimesheetItem($obj, $timesheet_item_id) {
		$data = array(
			'timesheet_item_id'=>$timesheet_item_id,
			'person_id'=>$obj->person_id,
			'timesheet_date'=>$obj->timesheet_date,
			'task_id'=>$obj->task_id,
			'project_id'=>$obj->project_id,
			'timesheet_hours'=>$obj->timesheet_hours,
			'timesheet_notes'=>$obj->timesheet_notes
		);
		//WHERE project_id = :project_id and person_id = :person_id and task_id = :task_id and timesheet_date = :timesheet_date";

		$this->db->where('project_id', $obj->project_id);
		$this->db->where('person_id', $obj->person_id);
		$this->db->where('task_id', $obj->task_id);
		$this->db->where('timesheet_date', $obj->timesheet_date);
		$this->db->update('timesheet_item', $data);
	}
	
	function deleteTimesheetItem($obj) {
		$data = array(
			'timesheet_item_id'=>$obj->timesheet_item_id,
			'person_id'=>$obj->person_id,
			'timesheet_date'=>$obj->timesheet_date,
			'task_id'=>$obj->task_id,
			'project_id'=>$obj->project_id,
			'timesheet_hours'=>$obj->timesheet_hours,
			'timesheet_notes'=>$obj->timesheet_notes
		);
		$this->db->where('project_id', $obj->project_id);
		$this->db->where('person_id', $obj->person_id);
		$this->db->where('task_id', $obj->task_id);
		$this->db->where('timesheet_date', $obj->timesheet_date);
		$this->db->delete('timesheet_item');
	}
	

/*	function display_archived_tasks() {
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
	}*/

}


