<?php
//queries for main report

class Budget_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function getAllBudgetedHours() {
		$rows = array(); //will hold all results
		$allhoursquery = $this->db->select('person.person_first_name');
		$allhoursquery = $this->db->select('person.person_last_name');
		$allhoursquery = $this->db->select('project.project_name');
		$allhoursquery = $this->db->select('project.project_billable');
		$allhoursquery = $this->db->select('project.project_id');
		//use this to get the right rate out of the task/project/person tables.
		$allhoursquery = $this->db->select('project.project_invoice_by');
		//if it is a project hourly rate use this
		$allhoursquery = $this->db->select('project.project_hourly_rate');
		//if it is a person hourly rate use this
		$allhoursquery = $this->db->select('person.person_hourly_rate');
		//if it is a task hourly rate use this
		$allhoursquery = $this->db->select('task.task_hourly_rate');
		
		$allhoursquery = $this->db->select('project.project_budget_by');
		$allhoursquery = $this->db->select('project.project_budget_total_hours');
		$allhoursquery = $this->db->select('project.project_budget_total_fees');
		$allhoursquery = $this->db->select('project_task.total_budget_hours as task_total_budget_hours');
		$allhoursquery = $this->db->select('project_person.total_budget_hours as person_total_budget_hours');
		$allhoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$allhoursquery = $this->db->from('person');
		$allhoursquery = $this->db->join('project_person', 'project_person.person_id = person.person_id');
		$allhoursquery = $this->db->join('project', 'project.project_id = project_person.project_id');
		$allhoursquery = $this->db->join('project_task', 'project_task.project_id = project.project_id');
		$allhoursquery = $this->db->join('task', 'task.task_id = project_task.task_id');
		$allhoursquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$allhoursquery = $this->db->group_by('project.project_name');
		$allhoursquery = $this->db->having('count(*) > 0');
		$allhoursquery = $this->db->get();	
		foreach($allhoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}

}
