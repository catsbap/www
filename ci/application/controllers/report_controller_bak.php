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
		$this->load->library('javascript');
		$this->data['library_src'] = $this->jquery->script();
		$this->data['script_foot'] = $this->jquery->_compile();
		//load breadcrumb
		$this->load->library('breadcrumb');
		//load form helper
		$this->load->helper('form');
		
		//$this->load->library('jquery');
		/******TAKE THIS OUT ONCE WE GET SEGMENTS WORKING!*******/
		//start to implement types of reports here, this is very heavy handed, we probably want this to be a helper.
		//THIS SHOULD FOLLOW THE DATE PICKER!
		$this->type = $this->input->get('type');
		$this->fromdate = $this->input->get('fromdate');
		$this->todate = $this->input->get('todate');
		
		//THIS IS SUPPOSED TO BE JUST WHATEVER IS IN THE URL!!
		//this sets up the appropriate date in the UI the first time the user comes into the page.
		//ideally, we want to update the URL client-side, but this is going to have to work for now,
		//^-----this means we update the url with jquery or javascript when the user selects the option from the drop-down on the page.
		//this is really the only way to make this work!!
		//12-22, wait to implement quarterly and custom.
		
		if ($this->type=='semimonthly') {
			$current_day = date_format(new DateTime($this->input->get('fromdate')), 'd');
			$date = new DateTime($this->input->get('fromdate'));
			$year_of_month = date_format($date->modify('last day of this month'), 'Y');
			$month_of_month = date_format($date->modify('last day of this month'), 'm');
			$middle_day = 16;
			if ($current_day >= $middle_day) {
				$date = new DateTime($this->input->get('fromdate'));
				$this->fromdate = $year_of_month . "-" . $month_of_month . "-" . $middle_day;
				$date = new DateTime($this->input->get('fromdate'));
				$last_day_of_month = $date->modify('last day of this month');
				$this->todate = date_format($last_day_of_month, 'Y-m-d');
			} else {
				$date = new DateTime($this->input->get('fromdate'));
				$date = $date->modify('first day of this month');
				$this->fromdate = date_format($date, 'Y-m-d');
				$date = new DateTime($this->fromdate);
				//this is always the last day of last month.
				$this->todate = date_format(date_add($date, date_interval_create_from_date_string($middle_day . ' days')), 'Y-m-d');
			}
		} elseif ($this->type=='month') {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('first day of this month');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('last day of this month');
			$this->todate = date_format($date, 'Y-m-d');			
		} elseif ($this->type=="week") {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('monday this week');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('+6 days');
			$this->todate = date_format($date, 'Y-m-d');
		} elseif ($this->type=="year") {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('first day of this year');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('last day of this year');
			$this->todate = date_format($date, 'Y-m-d');
		}
/****************************/
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
		if ($this->type=='semimonthly') {
			$current_day = date_format(new DateTime($this->input->get('fromdate')), 'd');
			$date = new DateTime($this->input->get('fromdate'));
			$year_of_month = date_format($date->modify('last day of this month'), 'Y');
			$month_of_month = date_format($date->modify('last day of this month'), 'm');
			$middle_day = 16;
			if ($current_day >= $middle_day) {
				$date = new DateTime($this->input->get('fromdate'));
				$this->fromdate = $year_of_month . "-" . $month_of_month . "-" . $middle_day;
				$date = new DateTime($this->input->get('fromdate'));
				$last_day_of_month = $date->modify('last day of this month');
				$this->todate = date_format($last_day_of_month, 'Y-m-d');
			} else {
				$date = new DateTime($this->input->get('fromdate'));
				$date = $date->modify('first day of this month');
				$this->fromdate = date_format($date, 'Y-m-d');
				$date = new DateTime($this->fromdate);
				//this is always the last day of last month.
				$this->todate = date_format(date_add($date, date_interval_create_from_date_string($middle_day . ' days')), 'Y-m-d');
			}
		} elseif ($this->type=='month') {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('first day of this month');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('last day of this month');
			$this->todate = date_format($date, 'Y-m-d');			
		} elseif ($this->type=="week") {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('monday this week');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('+6 days');
			$this->todate = date_format($date, 'Y-m-d');
		} elseif ($this->type=="year") {
			$first_day = new DateTime($this->input->get('fromdate'));
			$date = $first_day->modify('first day of this year');
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('last day of this year');
			$this->todate = date_format($date, 'Y-m-d');
		}
		$this->data['from_date'] = $this->fromdate;
		$this->data['to_date'] = $this->todate;
		
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    $this->data['picker'] = $mypicker;
	    //time tracker url code
	    $this->load->library('TimeTrackerUrls');   
	    $this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
		$this->load->library('menu');
		$this->menu_pages = array(
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=clients" => "Clients",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=projects" => "Projects",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=tasks" => "Tasks",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=staff" => "Staff"
                );
 
				//get the name of the active page
				//$this->CI->load->library('uri');
				$this->active = $this->uri->segment(1);
				//will this work for the breadcrumb?
				$url = current_url();
				$this->data['current_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
				$this->data['last_url'] = $_SERVER['HTTP_REFERER'];
						//error_log($this->data['last_url']);

					$this->breadcrumb->clear();					
				//setup the menu and load it ready for display in the view
				$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
				$this->data['css'] = $this->css;
				$this->data['base'] = $this->base;
		
		//get out all the data at the aggregate level, all data grouped by client, project, task, person.
		$this->load->model('Report_model', '', TRUE);
		$all_data = $this->Report_model->getAllHours($this->todate, $this->fromdate);
		if (empty($all_data))
				{
					//no data has been tracked yet.
					$rate_temp['total_time'][] = 0.00;
					$rate_temp['billable_rate'][] = 0.00;
					$rate_temp['billable_time'][] = 0.00;
					$this->data['rate_temp'] = 0.00;
			//		//return();
				}
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
					} elseif ($data['project_invoice_by'] == "Do not apply hourly rate") {
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
		//print_r($rate_temp);
		
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
	
	
	//*SHOWS THE PROJECTS
	function client_report() {
	//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		//error_log(print_r($this->data['breadcrumb']));
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
		//error_log(print_r($this->data['breadcrumb'],true));
		$this->data['project_url'] = $project_url;
		$this->data['client_name'] = $this->Report_model->getClientName($_GET["client_id"]);
		$data = $this->data;
		$this->load->view('header_view');
		$this->load->view('report_client_view', $data);
	}
	
	//**SHOWS THE TASKS
	function project_report() {
		//jquery/js stuff//
		$this->data['library_src'] = $this->jquery->script();
		$this->data['script_foot'] = $this->jquery->_compile();
		//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['last_url']); // this will be a link
		$this->breadcrumb->add_crumb('> This Client', $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		//error_log(print_r($this->data['breadcrumb']));
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
			$task_id = array();
			foreach($rate_temp['task_id'] as $key=>$val) {
				if (($tasks['task_id'] == $rate_temp['task_id'][$key]) && ($tasks['project_id'] == $rate_temp['project_id'][$key])) {		
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['project_id'], $tasks['task_name'], $this->data['controller'], $this->data['view']);
					//get the task ID to pass of to the person url
					$task_id[] = $rate_temp['task_id'][$key];
					$running_total_time = $rate_temp['total_time'][$key] + $running_total_time;
					$running_billable_time = $rate_temp['billable_time'][$key] + $running_billable_time;
					$running_billable_rate = money_format('%i', $rate_temp['billable_rate'][$key] + $running_billable_rate);
				}
			}
			
			$task_url[]['task_url'] = $anchored_task_url;
			$task_url[]['task_total_hours'] = $running_total_time;
			$task_url[]['task_billable_hours'] = $running_billable_time;
			$task_url[]['task_total_rate'] = $running_billable_rate;
			foreach ($task_id as $task) {
				$task_url[]['task_id'] = $task;
			}			
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
		$this->data['rate_temp'];
		$this->data['task_url'] = $task_url;
		$this->data['project_name'] = $this->Report_model->getProjectName($_GET["project_id"]);
		$this->data['project_id'] = $_GET["project_id"];
		$data = $this->data;
		
		
		$this->load->view('header_view');
		//LIFESPAN REPORT
		if ($this->type=="lifespan") {
				$data['results'] = $this->Report_model->getProjectLifespan($this->data['project_id']);
				
				//the lifespan report includes billable hours for the project. Get them out of the $data variable, which has already been created.
				//get out how we invoice this project.
				$invoice_by = $data['results'][0]->project_invoice_by;
				$data['billable_hours'] = 0;
				$data['billable_amount'] = 0.0;
				if ($invoice_by == 'Project hourly rate') {
					//I think we're going to have to rethink this..
					//$data['billable_hours'] = $data['results'][0]->timesheet_	
					$data['billable_hours'] = '999';
					$data['billable_amount'] = '999';
				} elseif ($invoice_by == 'Person hourly rate') {
					$data['billable_hours'] = '999';
					$data['billable_amount'] = '999';				
				} elseif ($invoice_by == 'Task hourly rate') {
					//get out the billable hours
					//not sure this is right, though. Double check this!
					$tasks = $this->data['task_url'];
					foreach ($tasks as $task) {
						if (isset($task['task_billable_hours'])) {
							$data['billable_hours'] = $data['billable_hours'] + $task['task_billable_hours'];
						}
						if (isset($task['task_total_rate'])) {
							$data['billable_amount'] = $data['billable_amount'] + $task['task_total_rate']; 
						}
					}
				} else {
					echo "This project is not invoiced; no data to display.";
				}
				//get out the budget information, regardless of the invoice type.
				if ($data['results'][0]->project_budget_by == "Total project hours") {
					$data['budget'] = $data['results'][0]->project_budget_total_hours;
				} elseif ($data['results'][0]->project_budget_by == "Total project fees") {
					$data['budget'] = $data['results'][0]->project_budget_total_fees;
				} elseif ($data['results'][0]->project_budget_by == "Hours per task") {
					$data['budget'] = $data['results'][0]->task_budget_hours;
				} elseif ($data['results'][0]->project_budget_by == "Hours per person") {
					$data['budget'] = $data['results'][0]->person_budget_hours;
				}
				
				$this->load->view('lifespan_view', $data);
			} else {
				$this->load->view('report_project_view', $data);
			}
	}
	
	////***SHOWS THE PEOPLE
	function task_report($task_id) {
		$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "person_report";
		
		//get all of the persons for this task for the given project id, in the URL.
		$personquery = $this->Report_model->getPersonsByTask($this->todate, $this->fromdate, $task_id);
		$person_url = array();
		foreach ($personquery as $persons) {	
			$running_total_time = '';
			$running_billable_time = '';
			$running_billable_rate = '';
			$anchored_person_url = '';
			foreach($rate_temp['person_id'] as $key=>$val) {
				if (($persons['person_id'] == $rate_temp['person_id'][$key]) && ($persons['task_id'] == $rate_temp['task_id'][$key]) && ($persons['project_id'] == $rate_temp['project_id'][$key])) {
					
					$anchored_person_url = $this->timetrackerurls->generate_person_url($persons['person_id'], $persons['person_first_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $rate_temp['total_time'][$key] + $running_total_time;
					$running_billable_time = $rate_temp['billable_time'] [$key] + $running_billable_time;
					$running_billable_rate = money_format('%i', $rate_temp['billable_rate'] [$key] + $running_billable_rate);
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
		$this->data['task_name'] = $this->Report_model->getTaskName($task_id);
		$data = $this->data;
		//print_r($data);
		//$this->load->view('report_task_view', $data);
	}

	
}
