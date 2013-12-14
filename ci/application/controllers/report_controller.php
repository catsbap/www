<?php

class Report_controller extends CI_Controller {
	var $base;
	var $css;
	var $to;
	var $from;
	var $client_id = "";
	
	function __construct() {
		//this is common code to all of these objects.
		parent::__construct();
		$this->fromdate = $this->input->get('fromdate');
		$this->todate = $this->input->get('todate');
		//get the information for the page we're viewing.
		if (isset($_GET['client_id'])) {
			$this->client_id = $this->input->get('client_id');
		} elseif (isset($_GET['project_id'])) {
			$this->project_id = $this->input->get('project_id');
		} elseif (isset($_GET['task_id'])) {
			$this->task_id = $this->input->get('task_id');
		} elseif (isset($_GET['person_id'])) {
			$this->person_id = $this->input->get('person_id');
		}
		//date picker code
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    $this->data['picker'] = $mypicker;
	    //time tracker url code
	    $this->load->library('TimeTrackerUrls');   
	    $this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
		$this->load->library('menu');
		$this->menu_pages = array(
                    "report?fromdate=$this->fromdate&todate=$this->todate&page=clients" => "Clients",
                    "report?fromdate=$this->fromdate&todate=$this->todate&page=projects" => "Projects",
                    "report?fromdate=$this->fromdate&todate=$this->todate&page=tasks" => "Tasks",
                    "report?fromdate=$this->fromdate&todate=$this->todate&page=staff" => "Staff"
                );
 
				//get the name of the active page
				//$this->CI->load->library('uri');
				$this->active = $this->uri->segment(1);
 
				//setup the menu and load it ready for display in the view
				$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
				$this->data['css'] = $this->css;
				$this->data['base'] = $this->base;
		
		//get out all the data at the aggregate level, all data grouped by client, project, task, person.
		$this->load->model('Report_model', '', TRUE);
		$all_data = $this->Report_model->getAllHours($this->todate, $this->fromdate);
			$this->data['controller'] = "report_controller";
			foreach ($all_data as $data) {
				$billable_time = "";
				$total_time = "";
				$billable_rate = "";
				if ($data['project_billable'] == 1) {
					if ($data['project_invoice_by'] == "Project hourly rate") {
						$total_time = $data['timesheet_hours'];
						if ($data['project_hourly_rate'] <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data['timesheet_hours'];
						}
						$billable_rate = money_format('%i', $data['project_hourly_rate'] * $billable_time);	
					} elseif ($data['project_invoice_by'] == "Person hourly rate") {
						$total_time = $data['timesheet_hours'];
						if ($data['person_hourly_rate'] <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data['timesheet_hours'];
						}
						$billable_rate = money_format('%i', $data['person_hourly_rate'] * $billable_time);
					} else if ($data['project_invoice_by'] == "Task hourly rate") {
						$total_time = $data['timesheet_hours'];
						if ($data['task_hourly_rate'] <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data['timesheet_hours'];
						}
						$billable_rate = money_format('%i', $data['task_hourly_rate'] * $billable_time);
					} elseif ($hours['project_invoice_by'] == "Do not apply hourly rate") {
						$total_time = $data['timesheet_hours'];
						$billable_time = "0.00";
						$billable_rate = "0.00";
					}
				} else {
					$total_time = $data['timesheet_hours'];
					$billable_time = "0.00";
					$billable_rate = "0.00";
				}
				$rate_temp[] = array();
				$rate_temp['total_time'][] = $total_time;
				$rate_temp['billable_rate'][] = $billable_rate;
				$rate_temp['client_id'][] = $data['client_id'];
				$rate_temp['project_id'][] = $data['project_id'];
				$rate_temp['task_id'][] = $data['task_id'];
				$rate_temp['person_id'][] = $data['person_id'];
				$rate_temp['billable_time'][] = $billable_time;
				
				$this->data['rate_temp'] = $rate_temp;
			}
		
		//this aggregates everything up to the top level, not sure we need this here.
		$rate = "";
		foreach ($rate_temp['billable_time'] as $ratetemp) {
			$rate = $rate + $ratetemp;
		}
		$this->data['billable_time'] = $rate;
		
		$rate = "";
		foreach ($rate_temp['total_time'] as $ratetemp) {
			$rate = $rate + $ratetemp;
		}
		$this->data['total_time'] = $rate;
		
		$rate = "";
		foreach ($rate_temp['billable_rate'] as $ratetemp) {
			$rate = $rate + $ratetemp;
		}
		$this->data['billable_rate'] = $rate;
	}
	
	
	function client_report() {
		$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "project_report";
		
		//get all of the projects for this time for the given client id, in the URL.
		$projectquery = $this->Report_model->getProjectsByClient($this->todate, $this->fromdate, $this->client_id);
		$project_url = array();

		foreach ($projectquery as $projects) {	
			$running_total_time = '';
			$running_billable_time = '';
			$running_billable_rate = '';
			$anchored_project_url = '';
			foreach($rate_temp['project_id'] as $key=>$val) {
				if ($projects['project_id'] == $rate_temp['project_id'][$key]) {		
					
					$anchored_project_url = $this->timetrackerurls->generate_project_url($projects['project_id'], $projects['project_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $rate_temp['total_time'][$key] + $running_total_time;
					$running_billable_time = $rate_temp['billable_time'][$key] + $running_billable_time;
					$running_billable_rate = money_format('%i', $rate_temp['billable_rate'][$key] + $running_billable_rate);
				}
			}
						
			$project_url[]['project_url'] = $anchored_project_url;
			$project_url[]['project_total_hours'] = $running_total_time;
			$project_url[]['project_billable_hours'] = $running_billable_time;
			$project_url[]['project_total_rate'] = $running_billable_rate;
		}
		
		$rate = "";
		$project_total_time = "";
		$project_billable_hours = "";
		$project_total_rate = "";

		foreach ($project_url as $key=>$value) {
			foreach ($value as $key=>$value) {
				if ($key == 'project_total_hours') {
					$project_total_time = $project_total_time + $value;
				}
				if ($key == 'project_billable_hours') {
					$project_billable_hours = $project_billable_hours + $value;
				}
				if ($key == 'project_total_rate') {
					$project_total_rate = $project_total_rate + $value;
				}
			}
			$this->data['total_time'] = $project_total_time;
			$this->data['billable_time'] = $project_billable_hours;
			$this->data['billable_rate'] = $project_total_rate;
		}

		$this->data['project_url'] = $project_url;
		$this->data['client_name'] = $this->Report_model->getClientName($_GET["client_id"]);
		$data = $this->data;
		$this->load->view('header_view');
		$this->load->view('report_client_view', $data);
	}
	
	function project_report() {
		$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "task_report";
		
		//get all of the tasks for this time for the given project id, in the URL.
		$taskquery = $this->Report_model->getTasksByProject($this->todate, $this->fromdate, $this->project_id);
		$task_url = array();
		foreach ($taskquery as $tasks) {	
			$running_total_time = '';
			$running_billable_time = '';
			$running_billable_rate = '';
			$anchored_task_url = '';
			foreach($rate_temp['task_id'] as $key=>$val) {
				if ($tasks['task_id'] == $rate_temp['task_id'][$key]) {		
					
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['task_id'], $tasks['task_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $rate_temp['total_time'][$key] + $running_total_time;
					$running_billable_time = $rate_temp['billable_time'][$key] + $running_billable_time;
					$running_billable_rate = money_format('%i', $rate_temp['billable_rate'][$key] + $running_billable_rate);
				}
			}
						
			$task_url[]['task_url'] = $anchored_task_url;
			$task_url[]['task_total_hours'] = $running_total_time;
			$task_url[]['task_billable_hours'] = $running_billable_time;
			$task_url[]['task_total_rate'] = $running_billable_rate;
		}
		
		$rate = "";
		$task_total_time = "";
		$task_billable_hours = "";
		$task_total_rate = "";

		foreach ($task_url as $key=>$value) {
			foreach ($value as $key=>$value) {
				if ($key == 'task_total_hours') {
					$task_total_time = $task_total_time + $value;
				}
				if ($key == 'task_billable_hours') {
					$task_billable_hours = $task_billable_hours + $value;
				}
				if ($key == 'task_total_rate') {
					$task_total_rate = $task_total_rate + $value;
				}
			}
			$this->data['total_time'] = $task_total_time;
			$this->data['billable_time'] = $task_billable_hours;
			$this->data['billable_rate'] = $task_total_rate;
		}

		$this->data['task_url'] = $task_url;
		$this->data['project_name'] = $this->Report_model->getProjectName($_GET["project_id"]);
		$data = $this->data;
		$this->load->view('header_view');
		$this->load->view('report_project_view', $data);
	}
	
	function task_report() {
		$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "person_report";
		
		//get all of the tasks for this time for the given project id, in the URL.
		$personquery = $this->Report_model->getPersonsByTask($this->todate, $this->fromdate, $this->task_id);
		//print_r($personquery);
		$person_url = array();
		foreach ($personquery as $persons) {	
			$running_total_time = '';
			$running_billable_time = '';
			$running_billable_rate = '';
			$anchored_person_url = '';
			foreach($rate_temp['person_id'] as $key=>$val) {
				if ($persons['person_id'] == $rate_temp['person_id'][$key]) {	
					
					$anchored_person_url = $this->timetrackerurls->generate_person_url($persons['person_id'], $persons['person_first_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $rate_temp['total_time'][$key] + $running_total_time;
					$running_billable_time = $rate_temp['billable_time'][$key] + $running_billable_time;
					$running_billable_rate = money_format('%i', $rate_temp['billable_rate'][$key] + $running_billable_rate);
				}
			}
						
			$person_url[]['person_url'] = $anchored_person_url;
			$person_url[]['person_total_hours'] = $running_total_time;
			$person_url[]['person_billable_hours'] = $running_billable_time;
			$person_url[]['person_total_rate'] = $running_billable_rate;
		}
		
		$rate = "";
		$person_total_time = "";
		$person_billable_hours = "";
		$person_total_rate = "";

		foreach ($person_url as $key=>$value) {
			foreach ($value as $key=>$value) {
				if ($key == 'person_total_hours') {
					$person_total_time = $person_total_time + $value;
				}
				if ($key == 'person_billable_hours') {
					$person_billable_hours = $person_billable_hours + $value;
				}
				if ($key == 'person_total_rate') {
					$person_total_rate = $person_total_rate + $value;
				}
			}
			$this->data['total_time'] = $person_total_time;
			$this->data['billable_time'] = $person_billable_hours;
			$this->data['billable_rate'] = $person_total_rate;
		}

		$this->data['person_url'] = $person_url;
		$this->data['task_name'] = $this->Report_model->getTaskName($_GET["task_id"]);
		$data = $this->data;
		$this->load->view('header_view');
		$this->load->view('report_task_view', $data);
	}

	
}
