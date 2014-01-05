<?php
/***this is the main controller for the repots section.*/


class Report extends CI_Controller {
	var $base;
	var $css;
	var $to;
	var $from;
	var $client_id = "";
	
	function __construct() {
		//this is common code to all of the pages that make up this part of the report.
		parent::__construct();
		$this->load->library('javascript');
		$data['library_src'] = $this->jquery->script();
		$data['script_foot'] = $this->jquery->_compile();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('type', 'week', 'trim');

		//start to implement types of reports here, this is very heavy handed, we probably want this to be a helper.
		//THIS SHOULD FOLLOW THE DATE PICKER!
		$this->type = $this->input->get('type');
		$this->fromdate = $this->input->get('fromdate');
		$this->todate = $this->input->get('todate');

			
		$client_id = $this->input->get('client_id');
		//date picker code
		$this->load->library('DatePicker');   
		//now we have the task object
		//$this->load->library('Task');
		$mypicker = $this->datepicker->show_picker();
	    $this->data['picker'] = $mypicker;
	    //time tracker url code
	    $this->load->library('TimeTrackerUrls');   
	    
		
		$this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
//		the menu is a third party library that lets us auto-generate the green menu on each page.
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
 
				//setup the menu and load it ready for display in the view
				$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
				$this->data['css'] = $this->css;
				$this->data['base'] = $this->base;
		}
	
	//this is the main index function
	function index() {
	    //load the model
	    $this->load->model('Report_model', '', TRUE);
	    

		$all_data = $this->Report_model->getAllHours($this->todate, $this->fromdate);
			//create the class here, so that if no data is available
			//it actually gets created anyway, with blank values.
			$data = new stdClass;
			$this->data['controller'] = "report_controller";
			foreach ($all_data as $data) {
				$billable_time = 0;
				$total_time = 0;
				$billable_amount = 0;
				//make sure we are only looking at billable projects.
				if ($data->project_billable == 1) {
					//the project is invoiced by project hourly rate.
					if ($data->project_invoice_by == "Project hourly rate") {
						if ($data->project_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						//billable amount is the rate for the project X the timesheet hours.
						$billable_amount = money_format('%i', $data->project_hourly_rate * $billable_time);	
					} elseif ($data->project_invoice_by == "Person hourly rate") {
						if ($data->person_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						//billable amount is the rate for the person X the timesheet hours.
						$billable_amount = money_format('%i', $data->person_hourly_rate * $billable_time);
					} else if ($data->project_invoice_by == "Task hourly rate") {
						if ($data->task_hourly_rate <= 0) {
							$billable_time = 0.00;
						} else {
							$billable_time = $data->timesheet_hours;
						}
						//billable amount is the rate for the task X the timesheet hours.
						$billable_amount = money_format('%i', $data->task_hourly_rate * $billable_time);
					} elseif ($data->project_invoice_by == "Do not apply hourly rate") {
						$billable_time = "0.00";
						$billable_amount = "0.00";
					}
				} else {
					//THE PROJECT IS NOT BILLABLE.
					$billable_time = "0.00";
					$billable_amount = "0.00";
				}
				//$rate_temp['total_time'][] = $total_time;
				$data->billable_amount = $billable_amount;
				$data->billable_time = $billable_time;
				//error_log(print_r($data, true));
			}
		
		//uninvoiced amount
		//wait to implement until invoicing is complete
		
		//top view common code
		//this aggregates all data at the total level
		//so we can display it in the UI.
		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		foreach ($all_data as $data) {
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}
		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		
		//put the date in the data variable to get it out of the view!
		$data->from_date = $this->fromdate;
		$data->to_date = $this->todate;
		$this->load->view('header_view');
		$this->load->view('top_view', $data);
		
		$this->input->get('page');
		if ($this->input->get('page') == "clients") {
		
		////////////////////////
		//****CLIENT DATA*****//
		///////////////////////
		
		$this->data['view'] = "client_report";
		$client_url = array();
		$client_url[]['client_billable_rate'] = array();
		$client_url[]['client_billable_time'] = array();
		$client_url[]['client_total_time'] = array();
		//clientquery returns an array.
		$clientquery = $this->Report_model->getClientHours($this->todate, $this->fromdate);
		foreach ($clientquery as $clients) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($all_data as $data) {
				if ($clients['client_id'] == $data->client_id) {		
					
					$anchored_client_url = $this->timetrackerurls->generate_client_url($clients['client_id'], $clients['client_name'], $this->data['controller'], $this->data['view']);
					
					$running_total_time = $data->timesheet_hours + $running_total_time;
					$running_billable_time = $data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $data->billable_amount + $running_billable_amount);
				}
			}
			
			$client_url[]['client_url'] = $anchored_client_url;
			$client_url[]['client_total_hours'] = $running_total_time;
			$client_url[]['client_billable_hours'] = $running_billable_time;
			$client_url[]['client_total_amount'] = $running_billable_amount;
		}
		
			$this->data['client_url'] = $client_url;
			$data = $this->data;
			$this->load->view('client_view', $data);
		} elseif ($this->input->get('page') == "projects") {
			//****PROJECT DATA******/
			//*********************//
		$this->data['view'] = "project_report";
		$project_url = array();
		$project_url[]['project_billable_rate'] = array();
		$project_url[]['project_billable_time'] = array();
		$project_url[]['project_total_time'] = array();
		$projectquery = $this->Report_model->getProjectHours($this->todate, $this->fromdate);
		foreach ($projectquery as $projects) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
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
		
			$this->data['project_url'] = $project_url;
			$data = $this->data;
			$this->load->view('project_view', $data);	
		} elseif ($this->input->get('page') == "tasks") {
			//****TASK DATA******/
		$this->data['view'] = "task_report";
		$task_url = array();
		$task_url[]['task_billable_rate'] = array();
		$task_url[]['task_billable_time'] = array();
		$task_url[]['task_total_time'] = array();
		$taskquery = $this->Report_model->getTaskHours($this->todate, $this->fromdate);
		foreach ($taskquery as $tasks) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($all_data as $data) {
				if ($tasks['task_id'] == $data->task_id) {		
					
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['task_id'], $tasks['task_name'], $this->data['controller'], $this->data['view']);
					$running_total_time = $data->timesheet_hours + $running_total_time;
					$running_billable_time = $data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $data->billable_amount + $running_billable_amount);
				}
			}
			
			$task_url[]['task_url'] = $anchored_task_url;
			$task_url[]['task_total_hours'] = $running_total_time;
			$task_url[]['task_billable_hours'] = $running_billable_time;
			$task_url[]['task_total_amount'] = $running_billable_amount;
		}
		
			$data->task_url = $task_url;
			//$data = $this->data;
			//PROJECT LIFESPAN REPORT
			if ($this->type=="lifespan") {
				$data = $this->Report_model->getProjectLifespan($this->todate, $this->fromdate);
				$this->load->view('lifespan_view', $data);
			} else {
				$this->load->view('task_view', $data);
			}
			//****PERSON DATA******//			
		} elseif ($this->input->get('page') == "staff") {
			$this->data['view'] = "person_report";
			$person_url = array();
		$person_url[]['person_billable_rate'] = array();
		$person_url[]['person_billable_time'] = array();
		$person_url[]['person_total_time'] = array();
		$personquery = $this->Report_model->getPersonHours($this->todate, $this->fromdate);
		foreach ($personquery as $persons) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($all_data as $data) {
				if ($persons['person_id'] == $data->person_id) {		
					
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
		
			$this->data['person_url'] = $person_url;
			$data = $this->data;
			$this->load->view('person_view', $data);		
		}
	}	
}
