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
		
		$this->type = $this->input->get('type');
		$this->fromdate = $this->input->get('fromdate');
		$this->todate = $this->input->get('todate');
		$this->data['from_date'] = $this->fromdate;
		$this->data['to_date'] = $this->todate;

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
		
		//the datepicker is the previous and next buttons.
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    $this->data['picker'] = $mypicker;
	    //time tracker url code
	    $this->load->library('TimeTrackerUrls');   
	    $this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
	
////MENU
		$this->load->library('menu');
		$this->menu_pages = array(
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=clients" => "Clients",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=projects" => "Projects",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=tasks" => "Tasks",
                    "report?fromdate=$this->fromdate&todate=$this->todate&type=$this->type&page=staff" => "Staff"
                );
 
				$this->active = $this->uri->segment(1);
				
//BREADCRUMB
				$url = current_url();
				$this->data['current_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
				$this->data['last_url'] = $_SERVER['HTTP_REFERER'];
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
			//this just adds the billable amounts and times to the object array.
			$data = new stdClass;
			$this->data['controller'] = "report_controller";
			foreach ($all_data as $data) {
				$billable_time = 0;
				$total_time = 0;
				$billable_amount = 0;
				if ($data->project_billable == 1) {
					if ($data->project_invoice_by == "Project hourly rate") {
						if ($data->project_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->project_hourly_rate * $billable_time);	
					} elseif ($data->project_invoice_by == "Person hourly rate") {
						if ($data->person_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->person_hourly_rate * $billable_time);
					} else if ($data->project_invoice_by == "Task hourly rate") {
						if ($data->task_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						$billable_amount = money_format('%i', $data->task_hourly_rate * $billable_time);
					} elseif ($data->project_invoice_by == "Do not apply hourly rate") {
						$billable_time = "0.00";
						$billable_rate = "0.00";
					}
				} else {
					$billable_time = "0.00";
					$billable_rate = "0.00";
				}
				$data->billable_amount = $billable_amount;				
				$data->billable_time = $billable_time;
			}
	}
	
	
	//SHOWS THE CLIENT REPORT, WHICH AGGREGATES THE DATA AT THE PROJECT LEVEL
	function client_report() {
	//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "project_report";
		
		
		//get all of the projects for this time for the given client id
		$projectquery = $this->Report_model->getProjectsByClient($this->todate, $this->fromdate, $this->client_id);
		$project_url = array();
		
		$data = new stdClass;
		$all_data = $this->data['all_data'];
		
		////////////////////////
		//PROJECT DATA ROLLUP//
		//////////////////////
		
		foreach ($projectquery as $projects) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_project_url = 0;
			foreach($all_data as $data) {
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
		
		//CLIENT AGGREGATE DATA TO DISPLAY IN THE TOP OF THE PAGE//
		foreach ($all_data as $data) {	
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}

		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		//////////////////////////////////////////////////////////
		
		$data->project_url = $project_url;
		$data->client_name = $this->Report_model->getClientName($_GET["client_id"]);
		$this->load->view('header_view');
		$this->load->view('report_client_view', $data);
	}
	
	//SHOWS THE PROJECT REPORT, WHICH AGGREGATES THE DATA AT THE PROJECTS AND SHOWS LINKS LEVEL
	function project_report() {
		
		//breadcrumb, build up as we go.
		$this->breadcrumb->add_crumb('Time Report', $this->data['last_url']); // this will be a link
		$this->breadcrumb->add_crumb("> UGH']", $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "task_report";
		
		
		
		//get all of the tasks for this time for the given project id
		$taskquery = $this->Report_model->getTasksByProject($this->todate, $this->fromdate, $this->project_id);
		$task_url = array();
		$data = new stdClass;
		$all_data = $this->data['all_data'];
		
		
		//handle the case where there are no tasks.
		//$this->data['task_url'] = $task_url;
		
		////////////////////////
		//TASK DATA ROLLUP//
		//////////////////////
		
		foreach ($taskquery as $tasks) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_task_url = '';
			$task_id = array();
			foreach($all_data as $data) {
				if (($tasks['task_id'] == $data->task_id) && ($tasks['project_id'] == $data->project_id)) {		
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['task_id'], $tasks['task_name'], $this->data['controller'], $this->data['view']);
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
		
		//CLIENT AGGREGATE DATA TO DISPLAY IN THE TOP OF THE PAGE//
		//this may need to be at the project level, not at the client level.//
		foreach ($all_data as $data) {
			//print_r($data);
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}
		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		/////////////////////////////////////////////////////////////////////////
		
		$this->data['project_name'] = $this->Report_model->getProjectName($_GET["project_id"]);
		$this->data['project_id'] = $_GET["project_id"];
		
		
		$this->load->view('header_view');
		
		////////////////////
		//LIFESPAN REPORT//
		//////////////////
		
		if ($this->type=="lifespan") {
				$this->data['results'] = $this->Report_model->getProjectLifespan($this->data['project_id']);
				
				//the lifespan report includes budgeted hours for the project. Get them out of the $this->data variable, which has already been created.
				//get out how we invoice this project. Do we allow projects to be budgeted when they are not invoiced? This code currently allows this, but won't show the invoice info.
				$invoice_by = $this->data['results'][0]->project_invoice_by;
				$this->data['billable_hours'] = 0;
				$this->data['billable_amount'] = 0.0;
				if ($invoice_by == 'Project hourly rate') {
					$projects = $this->Report_model->getLifespanByProject($this->data['results'][0]->to_date, $this->data['results'][0]->from_date, $this->data['project_id']);
					//get out all of the hours for this project.
					foreach ($projects as $project) {
						$this->data['billable_hours'] = $project['timesheet_hours'];
						$this->data['billable_amount'] = $project['project_hourly_rate'] * $project['timesheet_hours'];;
						$this->data['rate'] = $project['project_hourly_rate'];
					}
				} elseif ($invoice_by == 'Person hourly rate') {
					//get out the people for this project
					$people = $this->Report_model->getLifespanPeopleByProject($this->data['results'][0]->to_date, $this->data['results'][0]->from_date, $this->data['project_id']);
					foreach($people as $person) {;
						$this->data['billable_hours'] = $person['timesheet_hours'];
						$this->data['billable_amount'] = $person['person_hourly_rate'] * $person['timesheet_hours'];
						$this->data['rate'] = $person['person_hourly_rate'];
					}				
				} elseif ($invoice_by == 'Task hourly rate') {
					//get out the tasks for this project
					$tasks =  $this->Report_model->getLifespanTasksByProject($this->data['results'][0]->to_date, $this->data['results'][0]->from_date, $this->data['project_id']);
					foreach ($tasks as $task) {
						$this->data['billable_hours'] = $task['timesheet_hours'];
						$this->data['billable_amount'] = $task['task_hourly_rate'] * $task['timesheet_hours']; 
						$this->data['rate'] = $task['task_hourly_rate'];
					}
				} else {
					echo "This project is not invoiced; no data to display.";
				}
				//get out the budget information, regardless of the invoice type.
				//THIS IS A BUSINESS QUESTION...IF A PROJECT IS INVOICED BY PERSON, CAN THEY BE BUDGETED BY TASK (FOR EXAMPLE). IF SO,
				//HOW WOULD WE KNOW WOULD WE KNOW THE TASK RATE?
				//FOR NOW, ASSUME THE INVOICE TYPE IS THE SAME AS THE BUDGET TYPE.
				if ($this->data['results'][0]->project_budget_by == "Total project hours") {
					//ACCORDING TO HARVEST, THE TOTAL  PROJECT HOURS IS ALL HOURS TRACKED TO THE PROJECT.
					$this->data['budget'] = $this->data['results'][0]->project_budget_total_hours;
					$this->data['spent'] = $this->data['billable_amount'];
				} elseif ($this->data['results'][0]->project_budget_by == "Total project fees") {
					//ACCORDING TO HARVEST, THE TOTAL PROJECT FEES ARE THE BILLABLE HOURS X THE HOURLY RATE.
					//the rates are based on the invoices, so they are managed above.
					$this->data['budget'] = $this->data['results'][0]->project_budget_total_fees;
					$this->data['spent'] = $this->data['billable_amount'];
				} elseif ($this->data['results'][0]->project_budget_by == "Hours per task") {
					//ACCORDING TO HARVEST, THE HOURS PER TASK ARE THE HOURS SPENT ON THE TASKS FOR THE PROJECT.
					$this->data['budget'] = $this->data['results'][0]->task_budget_hours;
					$this->data['spent'] = $this->data['billable_amount'];
				} elseif ($this->data['results'][0]->project_budget_by == "Hours per person") {
					//ACCORDING TO HARVEST, THE HOURS PER PERSON ARE THE HOURS SPENT THE PEOPLE ON THE PROJECT.
					$this->data['budget'] = $this->data['results'][0]->person_budget_hours;
					$this->data['spent'] = $this->data['billable_amount'];
				}
				
				$this->load->view('lifespan_view', $data);
			} else {
				$this->load->view('report_project_view', $data);
			}
	}
	
	//SHOWS THE TASK REPORT, WHICH AGGREGATES THE DATA AT THE PERSON LEVEL
	function task_report() {
		//breadcrumb, build up as we go.
		
		$this->breadcrumb->add_crumb('Time Report', $this->data['last_url']); // this will be a link
		$this->breadcrumb->add_crumb(":{", $this->data['current_url']); // this will be a link
		$this->data['breadcrumb'] =  $this->breadcrumb->output();
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "person_report";
		
		//get all of the persons for this task for the given task id
		$personquery = $this->Report_model->getPersonsByTask($this->todate, $this->fromdate, $this->task_id);
		$person_url = array();
		$data = new stdClass;
		$all_data = $this->data['all_data'];
		
		////////////////////////
		//PERSON DATA ROLLUP//
		//////////////////////
		foreach ($personquery as $persons) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_person_url = 0;
			foreach($all_data as $data) {
				if (($persons['person_id'] == $data->person_id) && ($persons['task_id'] == $data->task_id)) {
					$anchored_person_url = $this->timetrackerurls->generate_person_url($persons['person_id'], $persons['person_first_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $data->timesheet_hours + $running_total_time;
					$running_billable_time = $data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $data->billable_amount + $running_billable_amount);
				}
			}
						
			$person_url[]['person_url'] = $anchored_person_url;
			$person_url[]['person_total_hours'] = $running_total_time;
			$person_url[]['person_billable_hours'] = $running_billable_time;
			$person_url[]['person_total_amount'] = $running_billable_amount;
		}
		//print_r($personquery);
		
		$rate = "";
		$person_total_time = "";
		$person_billable_hours = "";
		$person_total_rate = "";
		
		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;


		//CLIENT AGGREGATE DATA TO DISPLAY IN THE TOP OF THE PAGE//
		//this may need to be at the project level, not at the client level.//
		foreach ($all_data as $data) {
			//print_r($data);
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}
		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		/////////////////////////////////////////////////////////////////////////
	
		$data->person_url = $person_url;
		$this->data['task_name'] = $this->Report_model->getTaskName($_GET["task_id"]);
		$this->data['task_id'] = $_GET["task_id"];
		$this->load->view('header_view');
		$this->load->view('report_task_view', $data);

	}

	
}
