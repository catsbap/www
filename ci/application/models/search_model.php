<?php
//queries for main report

class Search_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	//populate the clients in the combobox that have time tracked
	//for the given timeframe.
	//this is also used to retrieve all client data for the timeframe.
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
		error_log(print_r($row,true));
		}
		return $rows;	
	}
	
	//populate the projects in the combobox that have time tracked
	//for the given timeframe.
	function getAllProjectTime($from, $to) {
		$rows = array(); //will hold all results
		$projecthoursquery = $this->db->select('client.client_name');
		$projecthoursquery = $this->db->select('project.project_name');
		$projecthoursquery = $this->db->from('project');
		$projecthoursquery = $this->db->join('client', 'client.client_id = project.client_id');
		$projecthoursquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projecthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projecthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$projecthoursquery = $this->db->group_by('project.project_name');
		$projecthoursquery = $this->db->having('count(*) > 0');
		$projecthoursquery = $this->db->get();	
		foreach($projecthoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//populate the tasks in the combobox that have time tracked
	//for the given timeframe.
	function getAllTaskTime($from, $to) {
		$rows = array(); //will hold all results
		$taskhoursquery = $this->db->select('task.task_name');
		$taskhoursquery = $this->db->from('task');
		$taskhoursquery = $this->db->join('timesheet_item', 'task.task_id = timesheet_item.task_id');
		$taskhoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$taskhoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$taskhoursquery = $this->db->group_by('task.task_name');
		$taskhoursquery = $this->db->having('count(*) > 0');
		$taskhoursquery = $this->db->get();	
		foreach($taskhoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//populate the people in the combobox that have time tracked
	//for the given timeframe.
	function getAllPeopleTime($from, $to) {
		$rows = array(); //will hold all results
		$personhoursquery = $this->db->select('person.person_first_name');
		$personhoursquery = $this->db->from('person');
		$personhoursquery = $this->db->join('timesheet_item', 'person.person_id = timesheet_item.person_id');
		$personhoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$personhoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$personhoursquery = $this->db->group_by('person.person_first_name');
		$personhoursquery = $this->db->having('count(*) > 0');
		$personhoursquery = $this->db->get();	
		foreach($personhoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//populate the departments in the combobox that have time tracked
	//for the given timeframe.
	function getAllDepartmentTime($from, $to) {
		$rows = array(); //will hold all results
		$departmenthoursquery = $this->db->select('person.person_department');
		$departmenthoursquery = $this->db->from('person');
		$departmenthoursquery = $this->db->join('timesheet_item', 'person.person_id = timesheet_item.person_id');
		$departmenthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$departmenthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$departmenthoursquery = $this->db->group_by('person.person_first_name');
		$departmenthoursquery = $this->db->having('count(*) > 0');
		$departmenthoursquery = $this->db->get();	
		foreach($departmenthoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
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
	
	function getAllHours($to, $from, $client_name, $project_name, $task_name, $person_name, $department_name, $activeToggle, $billableDropDown) {
		$rows = array(); //will hold all results
		$allhoursquery = $this->db->select('timesheet_item.timesheet_date');
		$allhoursquery = $this->db->select('client.client_id');
		$allhoursquery = $this->db->select('client.client_name');
		$allhoursquery = $this->db->select('project.project_name');
		$allhoursquery = $this->db->select('task.task_name');
		$allhoursquery = $this->db->select('person.person_first_name');
		$allhoursquery = $this->db->select('person.person_department');
		$allhoursquery = $this->db->select('project.project_billable');
		$allhoursquery = $this->db->select('project.project_invoice_by');
		$allhoursquery = $this->db->select('project.project_id');
		$allhoursquery = $this->db->select('project.project_hourly_rate');
		$allhoursquery = $this->db->select('person.person_hourly_rate');
		$allhoursquery = $this->db->select('person.person_id');
		$allhoursquery = $this->db->select('task.task_hourly_rate');
		$allhoursquery = $this->db->select('timesheet_item.task_id');
		$allhoursquery = $this->db->select('timesheet_item.person_id');
		$allhoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$allhoursquery = $this->db->from('client');
		$allhoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$allhoursquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$allhoursquery = $this->db->join('task', 'timesheet_item.task_id = task.task_id');
		$allhoursquery = $this->db->join('person', 'timesheet_item.person_id = person.person_id');
		$allhoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$allhoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$allhoursquery = $this->db->where('client.client_name like', $client_name);
		$allhoursquery = $this->db->where('project.project_name like', $project_name);
		$allhoursquery = $this->db->where('project.project_archived like', $activeToggle);
		$allhoursquery = $this->db->where('project.project_billable like', $billableDropDown);
		$allhoursquery = $this->db->where('task.task_name like', $task_name);
		$allhoursquery = $this->db->where('person.person_first_name like', $person_name);
		$allhoursquery = $this->db->where('person.person_department like', $department_name);
		$allhoursquery = $this->db->group_by('timesheet_item.timesheet_date');
		$allhoursquery = $this->db->group_by('client.client_name');
		$allhoursquery = $this->db->group_by('project.project_name');
		$allhoursquery = $this->db->group_by('person.person_id');
		$allhoursquery = $this->db->group_by('task.task_id');
		$allhoursquery = $this->db->order_by('timesheet_item.timesheet_date');
		$allhoursquery = $this->db->order_by('client.client_name');
		$allhoursquery = $this->db->order_by('project.project_name');
		$allhoursquery = $this->db->order_by('task.task_name');
		$allhoursquery = $this->db->order_by('person.person_first_name');
		$allhoursquery = $this->db->order_by('person.person_department');
		$allhoursquery = $this->db->having('count(*) > 0');
		$allhoursquery = $this->db->get();	
		foreach($allhoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}

}
