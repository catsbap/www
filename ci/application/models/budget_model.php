<?php
//queries for main report

class Budget_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
		
	function getAllBudgetedHours($from, $to) {
		$rows = array(); //will hold all results
		$allhoursquery = $this->db->select('client.client_name');
		$allhoursquery = $this->db->select('project.project_name');
		$allhoursquery = $this->db->select('project.project_id');
		$allhoursquery = $this->db->select('project.project_budget_by');
		$allhoursquery = $this->db->select('project_task.total_budget_hours');
		$allhoursquery = $this->db->select('project_person.total_budget_hours');
		$allhoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$allhoursquery = $this->db->from('client');
		$allhoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$allhoursquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$allhoursquery = $this->db->join('project_person', 'project_person.project_id = project.project_id');
		$allhoursquery = $this->db->join('project_task', 'project_task.project_id = project.project_id');
		$allhoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$allhoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$allhoursquery = $this->db->group_by('client.client_name');
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
