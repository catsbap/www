<?php

class Report_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	//client queries to be used in the client view, called from report controller
	function getClientName($client_id) {
		$query = $this->db->select('client.client_name');
		$query = $this->db->from('client');
		$query = $this->db->where('client.client_id =', $client_id);
		$query = $this->db->get();	
		return $query->result();
	}
	
	function getProjectName($project_id) {
		$query = $this->db->select('project.project_name');
		$query = $this->db->from('project');
		$query = $this->db->where('project.project_id =', $project_id);
		$query = $this->db->get();	
		return $query->result();
	}
	
	function getTaskName($task_id) {
		$query = $this->db->select('task.task_name');
		$query = $this->db->from('task');
		$query = $this->db->where('task.task_id =', $task_id);
		$query = $this->db->get();	
		return $query->result();
	}
	
	////WE NEED THIS FUNCTION FOR THE PERSON LIBRARY AND DROP DOWN IN TASKS
	function getTaskId($task_name) {
		$query = $this->db->select('task.task_id');
		$query = $this->db->from('task');
		$query = $this->db->where('task.task_name =', $task_name);
		$query = $this->db->get();	
		return $query->result();
	}
	
	function getPersonName($task_id) {
		$query = $this->db->select('person.person_first_name');
		$query = $this->db->from('person');
		$query = $this->db->join('project_person', 'project_person.person_id = person.person_id');
		$query = $this->db->join('project_task', 'project_task.project_id = project_person.project_id');		
		$query = $this->db->where('project_task.task_id =', $task_id);
		$query = $this->db->get();	
		return $query->result();
	}

	function getClientHours($to, $from) {
		//active projects only?
		$activeToggle = '%';
		if(isset($_GET['toggleVar'])) {
			if($_GET['toggleVar'] == 0) {
				$activeToggle = 0;
			}
		}
		$rows = array(); //will hold all results
		$clienthoursquery = $this->db->select('client.client_name');
		$clienthoursquery = $this->db->select('client.client_id');
		$clienthoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$clienthoursquery = $this->db->from('client');
		$clienthoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$clienthoursquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$clienthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$clienthoursquery = $this->db->where('project.project_archived like', $activeToggle);
		$clienthoursquery = $this->db->group_by('client.client_name');
		$clienthoursquery = $this->db->having('count(*) > 0');
		$clienthoursquery = $this->db->get();	
		foreach($clienthoursquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	function getProjectHours($to, $from) {
		//active projects only?
		$activeToggle = '%';
		if(isset($_GET['toggleVar'])) {
			if($_GET['toggleVar'] == 0) {
				$activeToggle = 0;
			}
		}
		$rows = array();
		$projecthoursquery = $this->db->select('project.project_name');
		$projecthoursquery = $this->db->select('project.project_id');
		$projecthoursquery = $this->db->select_sum('timesheet_hours');
		$projecthoursquery = $this->db->from('project');
		$projecthoursquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projecthoursquery = $this->db->where('timesheet_date <=', $to);
		$projecthoursquery = $this->db->where('timesheet_date >=', $from);
		$projecthoursquery = $this->db->where('project.project_archived like', $activeToggle);
		$projecthoursquery = $this->db->group_by('project.project_name');
		$projecthoursquery = $this->db->having('count(*) > 0');
		$projecthoursquery = $this->db->get();	
		foreach($projecthoursquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
	function getTaskHours($to, $from) {
		//active projects only?
		$activeToggle = '%';
		if(isset($_GET['toggleVar'])) {
			if($_GET['toggleVar'] == 0) {
				$activeToggle = 0;
			}
		}
		$rows = array();
		$taskhoursquery = $this->db->select('task.task_name');
		$taskhoursquery = $this->db->select('task.task_id');
		$taskhoursquery = $this->db->select_sum('timesheet_hours');
		$taskhoursquery = $this->db->from('task');
		$taskhoursquery = $this->db->join('timesheet_item', 'task.task_id = timesheet_item.task_id');
		$taskhoursquery = $this->db->join('project_task', 'project_task.task_id = task.task_id');
		$taskhoursquery = $this->db->join('project', 'project.project_id = project_task.project_id');
		$taskhoursquery = $this->db->where('timesheet_date <=', $to);
		$taskhoursquery = $this->db->where('timesheet_date >=', $from);
		$taskhoursquery = $this->db->where('project.project_archived like', $activeToggle);
		$taskhoursquery = $this->db->group_by('task.task_name');
		$taskhoursquery = $this->db->having('count(*) > 0');
		$taskhoursquery = $this->db->get();	
		foreach($taskhoursquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
	function getPersonHours($to, $from) {
		$rows = array();
		$personhoursquery = $this->db->select('person.person_first_name');
		$personhoursquery = $this->db->select('person.person_id');
		$personhoursquery = $this->db->select_sum('timesheet_hours');
		$personhoursquery = $this->db->from('person');
		$personhoursquery = $this->db->join('timesheet_item', 'person.person_id = timesheet_item.person_id');
		$personhoursquery = $this->db->where('timesheet_date <=', $to);
		$personhoursquery = $this->db->where('timesheet_date >=', $from);
		$personhoursquery = $this->db->group_by('person.person_first_name');
		$personhoursquery = $this->db->having('count(*) > 0');
		$personhoursquery = $this->db->get();	
		foreach($personhoursquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;
	}
	
	function getProjectsByClient($to, $from, $client_id) {
		$rows = array();
		$projectquery = $this->db->select('project.project_name');
		$projectquery = $this->db->select('project.project_id');
		//$projectquery = $this->db->select('client.client_name');
		//$projectquery = $this->db->select('client.client_id');
		$projectquery = $this->db->from('project');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('client', 'client.client_id = project.client_id');
		$projectquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projectquery = $this->db->where('client.client_id =', $client_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('project.project_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//this just gets the client out of the db so we have it for our detailed time report URLS.
	function getClientsByProject($project_id) {
		$rows = array();
		$projectquery = $this->db->select('client.client_name');
		$projectquery = $this->db->select('client.client_id');
		$projectquery = $this->db->from('client');
		//$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('project', 'client.client_id = project.client_id');
		//$projectquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projectquery = $this->db->where('project.project_id =', $project_id);
		//$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		//$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//this just gets the project out of the db so we have it for our detailed time report URLS.
	function getProjectByTask($task_id) {
		$rows = array();
		$projectquery = $this->db->select('project.project_name');
		$projectquery = $this->db->select('project.project_id');
		$projectquery = $this->db->from('project');
		//$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('project_task', 'project_task.project_id = project.project_id');
		//$projectquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projectquery = $this->db->where('project_task.task_id =', $task_id);
		//$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		//$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('project.project_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}	
	
	
	function getTasksByProject($to, $from, $project_id) {
		$rows = array();
		$projectquery = $this->db->select('task.task_name');
		$projectquery = $this->db->select('task.task_id');
		$projectquery = $this->db->select('project_task.project_id');
		//$projectquery = $this->db->select('client.client_name');
		//$projectquery = $this->db->select('client.client_id');
		$projectquery = $this->db->from('task');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('project_task', 'project_task.task_id = task.task_id');
		$projectquery = $this->db->join('timesheet_item', 'timesheet_item.task_id = project_task.task_id');
		$projectquery = $this->db->where('project_task.project_id =', $project_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('task.task_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	function getPersonsByTask($to, $from, $task_id) {
		//echo $task_id;
		//echo $to;
		//echo $from;
		$rows = array();
		$projectquery = $this->db->select('person.person_first_name');
		$projectquery = $this->db->select('person.person_id');
		$projectquery = $this->db->select('timesheet_item.task_id');
		$projectquery = $this->db->from('person');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('project_person', 'project_person.person_id = person.person_id');
		//$projectquery = $this->db->join('project_task', 'project_task.project_id = project_person.project_id');
		$projectquery = $this->db->join('timesheet_item', 'timesheet_item.person_id = person.person_id');
		$projectquery = $this->db->where('timesheet_item.task_id =', $task_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$projectquery = $this->db->group_by('person.person_first_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	function getLifespanByProject($to, $from, $project_id) {
		$rows = array();
		$projectquery = $this->db->select('project.project_name');
		$projectquery = $this->db->select('project.project_id');
		$projectquery = $this->db->select('project.project_hourly_rate');
		$projectquery = $this->db->select('timesheet_item.project_id');
		$projectquery = $this->db->from('project');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$projectquery = $this->db->where('timesheet_item.project_id =', $project_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('timesheet_item.project_id');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}

	function getLifespanTasksByProject($to, $from, $project_id) {
		$rows = array();
		$projectquery = $this->db->select('task.task_name');
		$projectquery = $this->db->select('task.task_id');
		$projectquery = $this->db->select('task.task_hourly_rate');
		$projectquery = $this->db->select('timesheet_item.task_id');
		$projectquery = $this->db->from('task');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('timesheet_item', 'timesheet_item.task_id = task.task_id');
		$projectquery = $this->db->where('timesheet_item.project_id =', $project_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		//$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->group_by('timesheet_item.project_id');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	
	function getLifespanPeopleByProject($to, $from, $project_id) {
		//get out all of the people that have worked on this project.
		$rows = array();
		$projectquery = $this->db->select('person.person_first_name');
		$projectquery = $this->db->select('person.person_id');
		$projectquery = $this->db->select('person.person_hourly_rate');
		$projectquery = $this->db->select('timesheet_item.project_id');
		$projectquery = $this->db->from('person');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('timesheet_item', 'timesheet_item.person_id = person.person_id');
		$projectquery = $this->db->where('timesheet_item.project_id =', $project_id);
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$projectquery = $this->db->group_by('person.person_first_name');
		$projectquery = $this->db->group_by('timesheet_item.project_id');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	

	function getProjects($to, $from) {
		$rows = array();
		$projectquery = $this->db->select('project.project_name');
		$projectquery = $this->db->select('project.project_id');
		$projectquery = $this->db->select('client.client_name');
		$projectquery = $this->db->select('client.client_id');
		$projectquery = $this->db->from('project');
		$projectquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projectquery = $this->db->join('client', 'client.client_id = project.client_id');
		$projectquery = $this->db->join('timesheet_item', 'project.project_id = timesheet_item.project_id');
		$projectquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projectquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$projectquery = $this->db->group_by('project.project_name');
		$projectquery = $this->db->group_by('client.client_name');
		$projectquery = $this->db->having('count(*) > 0');
		$projectquery = $this->db->get();	
		foreach($projectquery->result_array() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//tasks****

	function getTasks($to, $from) {
		$taskquery = $this->db->select('task.task_name');
		$taskquery = $this->db->from('task');
		$taskquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$taskquery = $this->db->join('timesheet_item', 'task.task_id = timesheet_item.task_id');
		$taskquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$taskquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$taskquery = $this->db->group_by('task.task_name');
		$taskquery = $this->db->having('count(*) > 0');
		$taskquery = $this->db->get();	
		foreach($taskquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//this is just a test to see how to load data into objects. 
	function getTaskObject() {
		$taskquery = $this->db->select('*');
		$taskquery = $this->db->from('task');
		$taskquery = $this->db->get();	
		foreach($taskquery->result('Task') as $row)
		{    
		$rows[] = $row; //add the fetched result to the result array;
		}
		//error_log(print_r($rows,true));
		return $rows;
	}
	
	//what if we get ALL data
	//YEAH!!
	function getAllHours($to, $from) {
		//active projects only?
		$activeToggle = '%';
		if(isset($_GET['toggleVar'])) {
			if($_GET['toggleVar'] == 0) {
				$activeToggle = 0;
			}
		}	
		$rows = array(); //will hold all results
		$projecthoursquery = $this->db->select('client.client_id');
		$projecthoursquery = $this->db->select('client.client_name');
		$projecthoursquery = $this->db->select('project.project_billable');
		$projecthoursquery = $this->db->select('project.project_invoice_by');
		$projecthoursquery = $this->db->select('project.project_id');
		$projecthoursquery = $this->db->select('project.project_hourly_rate');
		$projecthoursquery = $this->db->select('person.person_hourly_rate');
		$projecthoursquery = $this->db->select('person.person_id');
		$projecthoursquery = $this->db->select('task.task_hourly_rate');
		$projecthoursquery = $this->db->select('timesheet_item.task_id');
		$projecthoursquery = $this->db->select('timesheet_item.person_id');
		$projecthoursquery = $this->db->select('timesheet_item.timesheet_date');
		$projecthoursquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$projecthoursquery = $this->db->from('client');
		$projecthoursquery = $this->db->join('project', 'project.client_id = client.client_id');
		$projecthoursquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$projecthoursquery = $this->db->join('task', 'timesheet_item.task_id = task.task_id');
		$projecthoursquery = $this->db->join('person', 'timesheet_item.person_id = person.person_id');
		$projecthoursquery = $this->db->where('timesheet_item.timesheet_date <=', $to);
		$projecthoursquery = $this->db->where('timesheet_item.timesheet_date >=', $from);
		$projecthoursquery = $this->db->where('project.project_archived like', $activeToggle);
		$projecthoursquery = $this->db->group_by('client.client_name');
		$projecthoursquery = $this->db->group_by('project.project_name');
		$projecthoursquery = $this->db->group_by('person.person_id');
		$projecthoursquery = $this->db->group_by('task.task_id');
		$projecthoursquery = $this->db->having('count(*) > 0');
		$projecthoursquery = $this->db->get();	
		foreach($projecthoursquery->result() as $row)
		{    
        $rows[] = $row; //add the fetched result to the result array;
		}
		return $rows;	
	}
	
	//project lifespan report
	//this is for all dates for the given project.
		function getProjectLifespan($project_id) {
		$lifespanquery = $this->db->select('project.project_id');
		$lifespanquery = $this->db->select('project.project_name');
		$lifespanquery = $this->db->select('project.project_billable');
		$lifespanquery = $this->db->select('project.project_budget_by');
		$lifespanquery = $this->db->select('project.project_budget_total_hours');
		$lifespanquery = $this->db->select('project.project_budget_total_fees');
		$lifespanquery = $this->db->select('project.project_invoice_by');
		$lifespanquery = $this->db->select('project.project_hourly_rate');
		$lifespanoquery = $this->db->select('person.person_hourly_rate');
		$lifespanquery = $this->db->select('task.task_name');
		$lifespanquery = $this->db->select('task.task_hourly_rate');
		$lifespanquery = $this->db->select('project_task.total_budget_hours as task_budget_hours');
		$lifespanquery = $this->db->select('project_person.total_budget_hours as person_budget_hours');
		$lifespanquery = $this->db->select_min('timesheet_item.timesheet_date', 'from_date');
		$lifespanquery = $this->db->select_max('timesheet_item.timesheet_date', 'to_date');
		$lifespanquery = $this->db->select_sum('timesheet_item.timesheet_hours');
		$lifespanquery = $this->db->from('project');
		$lifespanquery = $this->db->join('timesheet_item', 'timesheet_item.project_id = project.project_id');
		$lifespanquery = $this->db->join('task', 'timesheet_item.task_id = task.task_id');
		$lifespanquery = $this->db->join('person', 'timesheet_item.person_id = person.person_id');
		$lifespanquery = $this->db->join('project_person', 'project_person.person_id = person.person_id');
		$lifespanquery = $this->db->join('project_task', 'project_task.project_id = project.project_id');
		$lifespanquery = $this->db->where('project.project_id =', $project_id);
		$lifespanquery = $this->db->group_by('project.project_name');
		$lifespanquery = $this->db->having('count(*) > 0');
		$lifespanquery = $this->db->get();
		return $lifespanquery->result();	
	}

}
