<?php

class Timesheet_controller extends CI_Controller {
	//JSON ENCODE FUNCTIONS HAVE BEEN PLACED IN DATA_CONTROLLER, CHECK THERE FOR JSON DATA USED
	//BY JQUERY.
	var $base;
	var $css;	
	
	function __construct() {
		parent::__construct();
		$this->base=$this->config->item('base_url');
		$this->css=$this->config->item('css');
		$this->load->helper('form');	
		$this->load->library('form_validation');
        $this->load->library('javascript');
		//$this->load->library('jquery');
		$this->data['library_src'] = $this->jquery->script();
		$this->data['script_foot'] = $this->jquery->_compile();
        $this->load->helper('url');
        $url = current_url();
		$this->data['site_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
		$this->load->model('Timesheet_model', '', TRUE);
		$this->load->model('Timesheet_item_model', '', TRUE);
		$this->load->model('Project_person_model', '', TRUE);
		$this->load->model('Project_task_model', '', TRUE);
		$this->load->model('Client_model', '', TRUE);
		$this->load->model('Project_model', '', TRUE);
		$this->load->model('Person_model', '', TRUE);

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		} else {
			//breaking the MVC model here to save time.
			//this is just pulling the func value from JS and then redirecting
			//to the model to save the timesheet if the user clicked the save button.
			if (isset($_POST["func"])) {
				if ($_POST["func"] == "saveTimesheet") {
					error_log(">>>>>>  save timesheet");
					if (isset($_POST["proc_type"])) {
						$processType = $_POST["proc_type"];
						$timesheet_items = $_POST["timesheetItems"];
						$delete_items = $_POST["deleteItems"];
						//error_log(print_r($_POST, true));
						//timesheet_submitted variable must be coming from somewhere else.
						//add this back later, for now simply set it to 0.
						$timesheet_submitted = "0";
						//$timesheet_submitted = $_POST["timesheet_submitted"];
						//error_log("KJHLKJH");
						//error_log(print_r($delete_items,true));
						redirect('timesheet_controller/save_timesheet?timesheet_items=' . utf8_encode($timesheet_items) . '&delete_items=' . utf8_encode($delete_items) . '&timesheet_submitted=' . utf8_encode($timesheet_submitted));
					}
				}
			} else {
				if (isset($_POST["save_timesheet_button"]) and $_POST["save_timesheet_button"] == "Save Timesheet") {
					redirect('timesheet_controller/save_timesheet');
				}
			}
		}	
	}
	
	
	function index() {
	
	}
	
	function display_timesheet() {
		//get out the logged in user based on their email from ion_auth
		//this is to allow login in dev. remove beyond.
		$this_user = $this->session->userdata( 'email' );
		if ($this_user == "admin@admin.com") {
			$this_user = "catsbap@gmail.com";
		}
		if ($this_user) {
			if ($this_user == "catsbap@gmail.com") {
				$data['person'] = 198;	
			} else {
				$data['person'] = $this->Person_model->display_person_by_email($this_user);
				$data['person'] = $data['person'][0]->person_id;
				error_log("HERE IS THE ARRAY");
				error_log(print_r($data['person'], true));
			}
		} else {
			echo("Something is wrong here...this person is not logged in and you shouldn't be seeing this, timesheet.php.");
			exit();
		}
		$task_id = $this->input->post('task_id');
		if ($task_id) {
			$data['processType'] = "E";
			$data['func'] = $this->input->post('func');
			error_log("here is func above" . $this->input->post('func'));
			$data['projects'] = $this->Project_person_model->display_projects_and_clients($data['person']);
			//error_log("Here is the project");
			//error_log(print_r($data['projects'], true));
			//I really don't think we're validating anything here, since the data is called from the DB.
			/*$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');*/
			//$this->Task_model->update_task($task_id);
		} else {
			$data['processType'] = "A";
			$data['func'] = $this->input->post('func');
			error_log("here is func" . $this->input->post('func'));
			$data['projects'] = $this->Project_person_model->display_projects_and_clients($data['person']);
			//$data['tasks'] = $this->Task_model->display_tasks();
			/*$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');*/
			//only update the task if the user is adding the task,
			//so there is a value in the func variable in the post.
			if ($this->input->post('func')) {
				//$this->Task_model->insert_task();
			}
			$this->load->view('header_view');
			$this->load->view('timesheet_view', $data);
		}
	}	
	
	function save_timesheet() {
		 
		//it needs to be inserted into the DB here.
		//because we are breaking the MVC model when the user first comes in,
		//we need to get the post data off of the URL (get).
		$timesheet_items = json_decode($this->input->get('timesheet_items'));
		$delete_items = json_decode($this->input->get('delete_items'));
		$timesheet_submitted = json_decode($this->input->get("timesheet_submitted"));
		//delete function
		error_log(">>>>>> delete_items: " . count($delete_items));
	
		if ($delete_items) {
			foreach($delete_items as $delete_item) {
				error_log(">>>>>>>" . $delete_item->person_id . ", " . $delete_item->project_id . ", " . $delete_item->task_id . ", " .  $delete_item->timesheet_date);
				$deltsi = array(
					"timesheet_item_id" => $delete_item->timesheet_item_id,
					"person_id" => $delete_item->person_id,
					"timesheet_date" => $delete_item->timesheet_date,
					"task_id" => $delete_item->task_id,
					"project_id" => $delete_item->project_id,
					"timesheet_hours" => $delete_item->timesheet_hours,
					"timesheet_notes" => $delete_item->timesheet_notes,
				);
				$delobject = new stdClass();
		
				foreach ($deltsi as $key => $value)
				{
					$delobject->$key = $value;
				}
				$this->Timesheet_model->deleteTimesheetItem($delobject);
				//$deltsi->deleteTimesheetItem($deltsi);
		}
	}

		
		foreach($timesheet_items as $timesheet_item) {
			$data['tsi'] = $this->Timesheet_model->getTimesheetItemForDatePersonProjectTask($timesheet_item->timesheet_date, $timesheet_item->person_id, $timesheet_item->project_id, $timesheet_item->task_id);
			//error_log("+++ " . $data['tsi']);
			if ( $data['tsi'] ) {
				$tsi = $data['tsi'];
				$tsi = array(
					"timesheet_item_id" => $timesheet_item->timesheet_item_id,
					"person_id" => $timesheet_item->person_id,
					"timesheet_date" => $timesheet_item->timesheet_date,
					"task_id" => $timesheet_item->task_id,
					"project_id" => $timesheet_item->project_id,
					"timesheet_hours" => $timesheet_item->timesheet_hours,
					"timesheet_notes" => null
				);
				$object = new stdClass();
		
				foreach ($tsi as $key => $value)
				{
					$object->$key = $value;
				}
				$this->Timesheet_model->updateTimesheetItem($object, $timesheet_item->timesheet_item_id);
				//old code from pre-migration to CI
				/*$tsi->setValue("timesheet_item_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->timesheet_item_id));
				$tsi->setValue("person_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->person_id));
				$tsi->setValue("timesheet_date", preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_date));
				$tsi->setValue("task_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->task_id));
				$tsi->setValue("project_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->project_id));
				$tsi->setValue("timesheet_hours", preg_replace("/[^ \.\:[0-9]]/", "", $timesheet_item->timesheet_hours));
				$tsi->setValue("timesheet_notes", preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_notes));
				$tsi->updateTimesheetItem($timesheet_item->timesheet_item_id);*/
			} else {
				//insert
				$newtsi = array(
					"timesheet_item_id" => $timesheet_item->timesheet_item_id,
					"person_id" => $timesheet_item->person_id,
					"timesheet_date" => $timesheet_item->timesheet_date,
					"task_id" => $timesheet_item->task_id,
					"project_id" => $timesheet_item->project_id,
					"timesheet_hours" => $timesheet_item->timesheet_hours,
					"timesheet_notes" => null
				);
				$object = new stdClass();
		
				foreach ($newtsi as $key => $value)
				{
					$object->$key = $value;
				}
				$this->Timesheet_model->insertTimesheetItem($object, $timesheet_item->timesheet_item_id);
				
			//	error_log(print_r($timesheet_item, TRUE));
			}
		}
		if ( $timesheet_submitted == 1 ) {
			Timesheet::submitTimesheet($timesheet_item->timesheet_item_id);
			$message = " and submitted for approval";
			error_log("timesheet submitted" );
		} else {
			$message = "";
		}
	}
	
	function pending_approval() {
		$this->load->view('header_view');
	}
	
	function unsubmitted() {
		$this->load->view('header_view');
	}
	
	function archive() {
		$this->load->view('header_view');
	}
}