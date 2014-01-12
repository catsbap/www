<?php
/***this is the main controller for the repots section.*/
//this is all of the data at the rolled up levels
//for clients, projects, tasks, and people.


class Report extends CI_Controller {
	var $base;
	var $css;
	var $to;
	var $from;
	var $client_id = "";
	
	
	function __construct() {
		//this is common code to all of the pages that make up these reports.
		parent::__construct();
		$this->load->library('javascript');
		$data['library_src'] = $this->jquery->script();
		$data['script_foot'] = $this->jquery->_compile();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('type', 'week', 'trim');
		//$this->type = $this->input->get('type');
		//$this->fromdate = $this->input->get('fromdate');
		//$this->todate = $this->input->get('todate');
		$this->type = $this->uri->segment(6);
		$this->fromdate = $this->uri->segment(3);
		$this->todate = $this->uri->segment(4);
		$this->page = $this->uri->segment(5);
		
			
		//date picker code (the date picker is the previous and next code in the UI).
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    $this->data['picker'] = $mypicker;
	    //time tracker url code (these functions return anchors for the pages. ie: clients returns anchors for all projects, projects returns anchors for all tasks, etc.
	    $this->load->library('TimeTrackerUrls');   
	    
		
		$this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
		
		
////////the menu is a third party library that lets us auto-generate the green menu on each page.
		$this->load->library('menu');
		$this->menu_pages = array(
                    "report/index/$this->fromdate/$this->todate/clients/$this->type" => "Clients",
                    "report/index/$this->fromdate/$this->todate/projects/$this->type" => "Projects",
                    "report/index/$this->fromdate/$this->todate/tasks/$this->type" => "Tasks",
                    "report/index/$this->fromdate/$this->todate/staff/$this->type" => "Staff"
                );
 
				$this->active = $this->uri->segment(1);
 
				//setup the menu and load it ready for display in the view
				$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
				$this->data['css'] = $this->css;
				$this->data['base'] = $this->base;
				
		//what if we move the main rollup to the constructor? Can we use it elsewhere, even in functions?
		$this->load->model('Report_model', '', TRUE);
	    
		//all data is all information used in these reports.
		//$data = new stdClass;
		$this->data['all_data'] = $this->Report_model->getAllHours($this->todate, $this->fromdate);
			//create the class here, so that if no data is available
			//it actually gets created anyway, with blank values.
			$data = new stdClass;
			$this->data['controller'] = "report";
			foreach ($this->data['all_data'] as $data) {
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
				//billable amount and billable time are now in the object.
				$data->billable_amount = $billable_amount;
				$data->billable_time = $billable_time;
			}
		}
//////end menu
	
////this is the main index function, so it is called whenever someone comes into the report controller.
	function index() {

		
		//uninvoiced amount
		//wait to implement until invoicing is complete
		
		//top view common code
		//ROLL UP at the top level.
		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		foreach ($this->data['all_data'] as $data) {
			$billable_time_holder = $billable_time_holder + $data->billable_time;
			$total_time_holder = $total_time_holder + $data->timesheet_hours;
			$billable_amount_holder = $billable_amount_holder + $data->billable_amount;
		}
		$data = new stdClass;
		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		
		//put the date in the data variable to get it out of the view!
		$data->from_date = $this->fromdate;
		$data->to_date = $this->todate;
		//always display the header and the top view data.
		$this->load->view('header_view');
		$this->load->view('top_view', $data);
		
		//$this->input->get('page');
		if ($this->page == "clients") {
		
		////////////////////////
		//****CLIENT DATA*****//
		///////////////////////
		
			$this->load->library('client_rollup');
			$this->data['client_url'] = $this->client_rollup->client_rollup();
			$data = $this->data;
			$this->load->view('client_view', $data);
		} elseif ($this->page == "projects") {
		
		////////////////////////
		//****PROJECT DATA*****//
		///////////////////////	
		$this->data['view'] = "project_report";
		$project_url = array();
		$project_url[]['project_billable_rate'] = array();
		$project_url[]['project_billable_time'] = array();
		$project_url[]['project_total_time'] = array();
		//ROLL UP all data at the project level.
		$projectquery = $this->Report_model->getProjectHours($this->todate, $this->fromdate);
		foreach ($projectquery as $projects) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($this->data['all_data'] as $data) {
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
		} elseif ($this->page == "tasks") {

		////////////////////////
		//****TASK DATA*****//
		///////////////////////		
		
		$this->data['view'] = "task_report";
		$task_url = array();
		$task_url[]['task_billable_rate'] = array();
		$task_url[]['task_billable_time'] = array();
		$task_url[]['task_total_time'] = array();
		//ROLL UP all data at the task level.
		$taskquery = $this->Report_model->getTaskHours($this->todate, $this->fromdate);
		foreach ($taskquery as $tasks) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($this->data['all_data'] as $data) {
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
		
			$this->data['task_url'] = $task_url;
			$data = $this->data;
			//PROJECT LIFESPAN REPORT
			if ($this->type=="lifespan") {
				echo "CAN YOU SEE ME?";
				$data = $this->Report_model->getProjectLifespan($this->todate, $this->fromdate);
				$this->load->view('lifespan_view', $data);
			} else {
				$this->load->view('task_view', $data);
			}
			//****PERSON DATA******//			
		} elseif ($this->page == "staff") {
			
			////////////////////////
			//****PERSON DATA*****//
			///////////////////////
			
		$this->data['view'] = "person_report";
		$person_url = array();
		$person_url[]['person_billable_rate'] = array();
		$person_url[]['person_billable_time'] = array();
		$person_url[]['person_total_time'] = array();
		//ROLL UP all data at the person level.
		$personquery = $this->Report_model->getPersonHours($this->todate, $this->fromdate);
		foreach ($personquery as $persons) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($this->data['all_data'] as $data) {
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
	
	
	//SUNDAY
	//LIFESPAN REPORT
	//HEADER
	//BREADCRUMB
	
	
	function client() {
		//client_rollup helper is used to show aggregate in top area of client report.
		//$this->load->library('client_rollup');
		//load breadcrumb
		$url = current_url();
		$this->data['current_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
		$this->data['last_url'] = $_SERVER['HTTP_REFERER'];
		$this->load->library('breadcrumb');	
		$this->breadcrumb->clear();	
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		/////////end breadcrumb
		//load datepicker
		//the datepicker is the previous and next buttons.
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    ///end date picker
	    //load menu
		$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
		//end menu
		$this->client_id = $this->uri->segment(7);
		$projectquery = $this->Report_model->getProjectsByClient($this->todate, $this->fromdate, $this->client_id);
		$project_url = array();
		$data = new stdClass;
		
		////////////////////////
		//PROJECT DATA ROLLUP//
		//////////////////////
		
		foreach ($projectquery as $projects) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_project_url = 0;
			foreach($this->data['all_data'] as $data) {
				if ($projects['project_id'] == $data->project_id) {		
					$anchored_project_url = $this->timetrackerurls->generate_project_url($projects['project_id'], $projects['project_name'], 'report', 'project');
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
		
		//CLIENT DATA TO DISPLAY IN THE TOP OF THE PAGE//
		//this can be just be the summarized project data that we already have above.
		foreach ($project_url as $key=>$value) {	
			//echo $key;
			foreach ($value as $key=>$val) {
				if ($key == "project_billable_hours") {
					$billable_time_holder = $billable_time_holder + $val;
				}
				if ($key == "project_total_hours")	{
					$total_time_holder = $total_time_holder + $val;
				}
				if ($key == "project_total_amount")	{
					$billable_amount_holder = $billable_amount_holder + $val;
				}
			}
		}
		

		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		$data->picker = $mypicker;
		$data->breadcrumb =  $this->breadcrumb->output();
		$data->menu =  $this->data['menu'];

		//////////////////////////////////////////////////////////
		
		$data->project_url = $project_url;
		$data->client_name = $this->Report_model->getClientName($this->client_id);
		$this->load->view('header_view');
		$this->load->view('report_client_view', $data);				
	}

	function project() {
		//load breadcrumb
		$url = current_url();
		$this->data['current_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
		$this->data['last_url'] = $_SERVER['HTTP_REFERER'];
		$this->load->library('breadcrumb');	
		$this->breadcrumb->clear();	
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		/////////end breadcrumb
		//load datepicker
		//the datepicker is the previous and next buttons.
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    ///end date picker
	    //load menu
		$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
		//end menu
		$this->project_id = $this->uri->segment(7);
		$taskquery = $this->Report_model->getTasksByProject($this->todate, $this->fromdate, $this->project_id);
		$task_url = array();
		$data = new stdClass;

		

		////////////////////////
		//TASK DATA ROLLUP//
		//////////////////////
		
		foreach ($taskquery as $tasks) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_task_url = '';
			$task_id = array();
			foreach($this->data['all_data'] as $data) {
				if (($tasks['task_id'] == $data->task_id) && ($tasks['project_id'] == $data->project_id)) {		
					$anchored_task_url = $this->timetrackerurls->generate_task_url($tasks['task_id'], $tasks['task_name'], 'report', 'task');
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
			//foreach ($task_id as $task) {
			//	$task_url[]['task_id'] = $task;
			//}			
			//$this->data['task_url'] = $task_url;
		}
		
		
		$rate = "";
		$project_total_time = "";
		$project_billable_hours = "";
		$project_total_rate = "";

		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		
		//PROJECT DATA TO DISPLAY IN THE TOP OF THE PAGE//
		//this can be just be the summarized project data that we already have above.
		foreach ($task_url as $key=>$value) {	
			//echo $key;
			foreach ($value as $key=>$val) {
				if ($key == "task_billable_hours") {
					$billable_time_holder = $billable_time_holder + $val;
				}
				if ($key == "task_total_hours")	{
					$total_time_holder = $total_time_holder + $val;
				}
				if ($key == "task_total_amount")	{
					$billable_amount_holder = $billable_amount_holder + $val;
				}
			}
		}
		

		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		$data->picker = $mypicker;
		$data->breadcrumb =  $this->breadcrumb->output();
		$data->menu =  $this->data['menu'];

		//////////////////////////////////////////////////////////
		
		$data->task_url = $task_url;
		$data->project_name = $this->Report_model->getProjectName($this->project_id);
		$this->load->view('header_view');
		$this->load->view('report_project_view', $data);				
	}
	
	function task() {
		//load breadcrumb
		$url = current_url();
		$this->data['current_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
		$this->data['last_url'] = $_SERVER['HTTP_REFERER'];
		$this->load->library('breadcrumb');	
		$this->breadcrumb->clear();	
		$this->breadcrumb->add_crumb('Time Report', $this->data['current_url']); // this will be a link
		/////////end breadcrumb
		//load datepicker
		//the datepicker is the previous and next buttons.
		$this->load->library('DatePicker');   
		$mypicker = $this->datepicker->show_picker();
	    ///end date picker
	    //load menu
		$this->data['menu'] = $this->menu->render($this->menu_pages, $this->active);
		//end menu
		$this->task_id = $this->uri->segment(7);
		$personquery = $this->Report_model->getPersonsByTask($this->todate, $this->fromdate, $this->task_id);
		$person_url = array();
		$data = new stdClass;
		

		////////////////////////
		//PERSON DATA ROLLUP//
		//////////////////////
		
		foreach ($personquery as $persons) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_person_url = 0;
			foreach($this->data['all_data'] as $data) {
				if (($persons['person_id'] == $data->person_id) && ($persons['task_id'] == $data->task_id)) {
					//$anchored_person_url = $this->timetrackerurls->generate_person_url($persons['person_id'], $persons['person_first_name'], 'report', 'person');
					//don't display the person as a URL.
					$anchored_person_url = $persons['person_first_name'];
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
		
		
		$rate = "";
		$project_total_time = "";
		$project_billable_hours = "";
		$project_total_rate = "";

		$billable_time_holder = 0;	
		$total_time_holder = 0;
		$billable_amount_holder = 0;
		
		//TASK DATA TO DISPLAY IN THE TOP OF THE PAGE//
		//this can be just be the summarized task data that we already have above.
		foreach ($person_url as $key=>$value) {	
			//echo $key;
			foreach ($value as $key=>$val) {
				if ($key == "person_billable_hours") {
					$billable_time_holder = $billable_time_holder + $val;
				}
				if ($key == "person_total_hours")	{
					$total_time_holder = $total_time_holder + $val;
				}
				if ($key == "person_total_amount")	{
					$billable_amount_holder = $billable_amount_holder + $val;
				}
			}
		}
		

		$data->aggregate_billable_time = $billable_time_holder;
		$data->aggregate_total_time = $total_time_holder;
		$data->aggregate_billable_amount = $billable_amount_holder;
		$data->picker = $mypicker;
		$data->breadcrumb =  $this->breadcrumb->output();
		$data->menu =  $this->data['menu'];

		//////////////////////////////////////////////////////////
		
		$data->person_url = $person_url;
		$data->task_name = $this->Report_model->getTaskName($this->task_id);
		$this->load->view('header_view');
		$this->load->view('report_task_view', $data);				
	}
	
	function person() {
		echo "invalid request.";
	}
}
