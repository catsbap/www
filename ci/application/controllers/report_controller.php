<?php

class Report_controller extends CI_Controller {
	var $base;
	var $css;
	var $to;
	var $from;
	var $client_id = "";
	
	function __construct() {
		//this is common code to all of these pages.
		parent::__construct();
		$this->load->library('javascript');
		$this->data['library_src'] = $this->jquery->script();
		$this->data['script_foot'] = $this->jquery->_compile();
		//load breadcrumb
		$this->load->library('breadcrumb');
		//load form helper
		$this->load->helper('form');
		
		//start to implement types of reports here, this is very heavy handed, we probably want this to be a helper.
		//THIS SHOULD FOLLOW THE DATE PICKER!
		$this->type = $this->input->get('type');
		$this->fromdate = $this->input->get('fromdate');
		$this->todate = $this->input->get('todate');
		
		

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
		//now that this is updated on the fly we don't need this.
		/*if ($this->type=='semimonthly') {
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
			//print_r($first_day);
			$date = $first_day->modify('first day of January this year');
			//print_r($first_day);
			$this->fromdate = date_format($date, 'Y-m-d');
			$date = $first_day->modify('last day of December this year');
			$this->todate = date_format($date, 'Y-m-d');
		}
		*/
		
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
		$this->data['all_data'] = $all_data;
		//create the class here, so that if no data is available
			//it actually gets created anyway, with blank values.
			$data = new stdClass;
			$this->data['controller'] = "report_controller";
			foreach ($all_data as $data) {
				$billable_time = 0;
				$total_time = 0;
				$billable_amount = 0;
				if ($data->project_billable == 1) {
					if ($data->project_invoice_by == "Project hourly rate") {
						//$total_time = $data['timesheet_hours'];
						if ($data->project_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->project_hourly_rate * $billable_time);	
					} elseif ($data->project_invoice_by == "Person hourly rate") {
						//$total_time = $data['timesheet_hours'];
						if ($data->person_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->person_hourly_rate * $billable_time);
					} else if ($data->project_invoice_by == "Task hourly rate") {
						//$total_time = $data['timesheet_hours'];
						if ($data->task_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->task_hourly_rate * $billable_time);
					} elseif ($data->project_invoice_by == "Do not apply hourly rate") {
						//$total_time = $data['timesheet_hours'];
						$billable_time = "0.00";
						$billable_rate = "0.00";
					}
				} else {
					//$total_time = $data['timesheet_hours'];
					$billable_time = "0.00";
					$billable_rate = "0.00";
				}
				//$rate_temp[] = array();
				//$rate_temp['total_time'][] = $total_time;
				$data->billable_amount = $billable_amount;				
				//$rate_temp['client_id'][] = $data['client_id'];
				//$rate_temp['project_id'][] = $data['project_id'];
				//$rate_temp['task_id'][] = $data['task_id'];
				//$rate_temp['person_id'][] = $data['person_id'];
				$data->billable_time = $billable_time;
				
				//$this->data['rate_temp'] = $rate_temp;
			}
	}
	
	
	//*SHOWS THE PROJECTS
	function client_report() {
	//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		//error_log(print_r($this->data['breadcrumb']));
		//$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "project_report";
		
		
		//get all of the projects for this time for the given client id, in the URL.
		$projectquery = $this->Report_model->getProjectsByClient($this->todate, $this->fromdate, $this->client_id);
		//print_r($projectquery);
		//echo($this->todate);
		//echo($this->fromdate);
		//echo($this->client_id);
		$project_url = array();
		
		$data = new stdClass;
		$all_data = $this->data['all_data'];
		//aggregate the various projects.
		foreach ($projectquery as $projects) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_project_url = 0;
			//$all_data = $this->data['all_data'];
			//print_r($all_data);
			//error_log(print_r($all_data,true));
			foreach($all_data as $data) {
								//echo($projects['project_id']);
								//echo($data->project_id);
				if ($projects['project_id'] == $data->project_id) {		
					$anchored_project_url = $this->timetrackerurls->generate_project_url($projects['project_id'], $projects['project_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $data->timesheet_hours + $running_total_time;
					$running_billable_time = $data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $data->billable_amount + $running_billable_amount);
				}
			}
						
			$project_url[]['project_url'] = $anchored_project_url;
			$project_url[]['project_total_hours'] = $running_total_time;
			$project_url[]['project_billable_hours'] = $running_billable_time;
			$project_url[]['project_total_amount'] = $running_billable_amount;
		}
		
		$rate = "";
		$project_total_time = "";
		$project_billable_hours = "";
		$project_total_rate = "";

		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		//$all_data = $this->Report_model->getAllHours($this->todate, $this->fromdate);
		foreach ($all_data as $data) {
			//print_r($data);
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}

		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		//error_log(print_r($this->data['breadcrumb'],true));
		$data->project_url = $project_url;
		$data->client_name = $this->Report_model->getClientName($_GET["client_id"]);
		//$data = $this->data;
		$this->load->view('header_view');
		$this->load->view('report_client_view', $data);
	}
	
	//**SHOWS THE TASKS
	function project_report() {
		//jquery/js stuff//
		//$this->data['library_src'] = $this->jquery->script();
		//$this->data['script_foot'] = $this->jquery->_compile();
		//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['last_url']); // this will be a link
		$this->breadcrumb->add_crumb('> This Client', $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		//error_log(print_r($this->data['breadcrumb']));
		//$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "task_report";
		
		
		//get all of the tasks for this time for the given project id, in the URL.
		$taskquery = $this->Report_model->getTasksByProject($this->todate, $this->fromdate, $this->project_id);
		$task_url = array();
		$data = new stdClass;
		$all_data = $this->data['all_data'];
		//handle the case where there are no tasks.
		$this->data['task_url'] = $task_url;
		//this aggregates the tasks
		foreach ($taskquery as $tasks) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			//$all_data = $this->data['all_data'];	
			$anchored_task_url = '';
			$task_id = array();
			foreach($all_data as $data) {
				if (($tasks['task_id'] == $data->task_id) && ($tasks['project_id'] == $data->project_id)) {		
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['project_id'], $tasks['task_name'], $this->data['controller'], $this->data['view']);
					//get the task ID to pass of to the person url
					$task_id[] = $data->task_id;
					$running_total_time = $data->timesheet_hours + $running_total_time;
					$running_billable_time = $data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $data->billable_amount + $running_billable_amount);
				}
			}
			
			$task_url[]['task_url'] = $anchored_task_url;
			$task_url[]['task_total_hours'] = $running_total_time;
			$task_url[]['task_billable_hours'] = $running_billable_time;
			$task_url[]['task_total_amount'] = $running_billable_amount;
			foreach ($task_id as $task) {
				$task_url[]['task_id'] = $task;
			}			
			$this->data['task_url'] = $task_url;
		}
		
		$rate = "";
		$task_total_time = "";
		$task_billable_hours = "";
		$task_total_rate = "";
		//$data = new stdClass;

		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		//$all_data = $this->Report_model->getAllHours($this->todate, $this->fromdate);
		foreach ($all_data as $data) {
			//print_r($data);
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}
		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		$this->data['project_name'] = $this->Report_model->getProjectName($_GET["project_id"]);
		$this->data['project_id'] = $_GET["project_id"];
		//$data = $this->data;
		
		
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
