<?php
//WE ARE WORKING ON SETTING UP THE QUERIES FOR THE MAIN REPORT.

class Search_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	//populate the clients in the combobox that have time tracked
	//for the given timeframe.
	function getAllClientTime($from, $to) {
		$rows = array(); //will hold all results
		$clienthoursquery = $this->db->select('client.client_name');
		$clienthoursquery = $this->db->from('client');
		$clienthoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$clienthoursquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$clienthoursquery = $this->db->group_by('client.client_name');
		$clienthoursquery = $this->db->having('count(*) > 0');
		$clienthoursquery = $this->db->get();	
		foreach($clienthoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//just testing this out for now, one field.
	function search($from, $to, $client_name) {
		$rows = array(); //will hold all results
		$clienthoursquery = $this->db->select('client.client_name');
		$clienthoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$clienthoursquery = $this->db->from('client');
		$clienthoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$clienthoursquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$clienthoursquery = $this->db->where('client.client_name =', $client_name);
		$clienthoursquery = $this->db->group_by('client.client_name');
		$clienthoursquery = $this->db->having('count(*) > 0');
		$clienthoursquery = $this->db->get();	
		foreach($clienthoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
}
