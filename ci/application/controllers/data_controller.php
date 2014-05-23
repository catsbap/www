<?php

//this controller returns data from existing models to various functions in jquery.
//it replaces the returnJSON.php file.
class Data_controller extends CI_Controller {
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
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	
	function index() {
	
	}
	
	function returnJSON() {
		//get the values out of the url. This will allow this to work in codeigniter without
		//having to dramatically alter the jquery associated with the UI.
		//get the function to call off the URL
		$func = $this->input->get('func', TRUE);
		$id = $this->input->get('id', TRUE);
		$collection = $this->input->get('collection', TRUE);
		$startDate = $this->input->get('startDate', TRUE);
		$endDate = $this->input->get('endDate', TRUE);
		if ($func == "returnTasksJSON") {
			$this->load->model('Task_model', '', TRUE);
			$data['tasks'] = $this->Task_model->display_archived_tasks();
			//error_log(print_r($data['tasks'], true));
			echo json_encode($data['tasks']);
		} elseif ($func == "returnClientJSON") {
			$this->load->model('Client_model', '', TRUE);
			$data['clients'] = $this->Client_model->display_clients();
			echo json_encode($data['clients']);
		} elseif ($func == "returnProjectJSON") {
			$this->load->model('Project_model', '', TRUE);
			$data['projects'] = $this->Project_model->display_projects();
			echo json_encode($data['projects']);
		} elseif ($func == "returnPeopleJSON") {
			$this->load->model('Person_model', '', TRUE);
			$data['people'] = $this->Person_model->display_people();
			echo json_encode($data['people']);
		} elseif ($func == "returnTimesheetJSON") {
			//load the models for getting the data.
			$this->load->model('Timesheet_model', '', TRUE);
			$this->load->model('Task_model', '', TRUE);
			$this->load->model('Project_model', '', TRUE);
			//$this->load->model('Timesheet_item_model', '', TRUE);
			if ($collection == "person") {
				if ($id != "") {
					error_log("id: " . $id . " startDate = " . $startDate . " endDate = " . $endDate);
					//see if this timesheet is in the database already
					$timesheets = $this->Timesheet_model->getTimesheetById($id, $startDate, $endDate);
					error_log("** " . print_r($timesheets,true));
					if ( !$timesheets ) {
						error_log(">>>>>> no timesheets. create new one.");
						//create an array with the data we already have
						$timesheets= array(
							"timesheet_id"=>"",
							"timesheet_approved"=>"",
							"timesheet_submitted"=>"",
							"timesheet_start_date"=>$startDate,
							"timesheet_end_date"=>$endDate,
							"person_id"=>$id
			
						);
						//put the timesheet in the database
						$timesheets = $this->Timesheet_model->insertTimesheet($id, $startDate, $endDate);
						
					} else {
					}
				} else {
					//error_log("dhlfkjhdlfkjhaflksjhlkajhflkjhakdsgjhlfhjlkjhdlfkjhsljshlkjdfhslkjhflkjhWe found the timesheet!");
				}
			}
			error_log("++++");
			error_log(count($timesheets));
			$timesheetJSON = array();
			foreach ($timesheets as $timesheet) {
				error_log("####>> " . $timesheet->timesheet_id);
				//get out all of the data for each timesheet item based on the timesheet_id.
				$data['timesheet_items'] = $this->Timesheet_model->getTimesheetItems($timesheet->timesheet_id);
				//error_log(">>> " . count($data['timesheet_items']));
				$tsItems_JSON = array();
				//////	ONCE THE DATA IS GOING INTO THE TIMESHEET TABLES THIS WILL
				//PROBABLY NOT WORK AND WILL NEED TO BE DEBUGGED.
				if ($data['timesheet_items']) {
					$this->load->model('Task_model', '', TRUE);
					$this->load->model('Project_model', '', TRUE);
					foreach ($data['timesheet_items'] as $timesheet_item) {
						$tName = $this->Task_model->getTaskName($timesheet_item->task_id);
						$pName = $this->Project_model->getProjectName($timesheet_item->project_id);
						$tsItems_JSON[] = array(
							"timesheet_item_id" => $timesheet_item->timesheet_item_id,
							"person_id" => $timesheet_item->person_id,
							"task_id" => $timesheet_item->task_id,
							"project_id" => $timesheet_item->project_id,
							"timesheet_date" => $timesheet_item->timesheet_date,
							"timesheet_hours" => $timesheet_item->timesheet_hours,
							"timesheet_notes" => $timesheet_item->timesheet_notes,
							"task_name" => $tName->task_name,
							"project_name" => $pName->project_name
						);
					}
				}
				$timesheetJSON[] = array(
				"timesheet_id" => $timesheet->timesheet_id,
				"timesheet_approved" => $timesheet->timesheet_approved,
				"timesheet_submitted" => $timesheet->timesheet_submitted,
				"timesheet_start_date" => $timesheet->timesheet_start_date,
				"timesheet_end_date" => $timesheet->timesheet_end_date,
				"person_id" => $timesheet->person_id,
				"timesheet_items" => $tsItems_JSON
				);
			}
					echo json_encode($timesheetJSON);
			
		} //close if
		//return $timesheetJSON;
						//error_log("OH CMON");
						//error_log(print_r($timesheetJSON, true));

	}//close function
} //close object declaration

//***OLD JS GU
/*function returnTimesheetsJSON($id, $collection, $startDate, $endDate) {
	if ($collection == "person") {
		if ($id != "") {
			error_log("id: " . $id . " startDate = " . $startDate . " endDate = " . $endDate);
			$timesheets = Timesheet::getTimesheetById($id, $startDate, $endDate);
			error_log("** " . $timesheets);
			if ( !$timesheets ) {
				error_log(">>>>>> no timesheets. create new one.");
				$timesheets= new Timesheet( array(
					"timesheet_id"=>"",
					"timesheet_approved"=>"",
					"timesheet_submitted"=>"",
					"timesheet_start_date"=>$startDate,
					"timesheet_end_date"=>$endDate,
					"person_id"=>$id
			
				));
				$timesheets->insertTimesheet($id, $startDate, $endDate);
				//$timesheet_items = array();
			}
 		} else {
			//$timesheet_items = Timesheet_Items::getTimesheetItems();
		}

	}
	*/
	
	/* THIS WAS ALREADY COMMENTED OUT
 else if ($collection == "timesheet") {
		if ($id != "") {
			$timesheets = Timesheet::getTimesheetById($id);
		} else {
			//$timesheets = Timesheet::getPeople(); //should be a way to get all timesheets for a project?
		}
	
	}

	//$timesheets = $timesheets[0];
	//error_log(">>> " . $timesheets);
END ALREADY COMMENTED OUT */


	/*error_log("++++");
	//error_log(count($timesheets));
	$timesheetJSON = array();
	foreach ($timesheets as $timesheet) {
		error_log("####>> " . $timesheet->getValue("timesheet_id"));
		$timesheet_items = Timesheet_Item::getTimesheetItems($timesheet->getValue("timesheet_id"));
		error_log(">>> " . count($timesheet_items));
		$tsItems_JSON = array();
		if ($timesheet_items) {
			foreach ($timesheet_items as $timesheet_item) {
				$tName = Task::getTaskName($timesheet_item->getValue("task_id"));
				$pName = Project::getProjectName($timesheet_item->getValue("project_id"));
				$tsItems_JSON[] = array(
					"timesheet_item_id" => $timesheet_item->getValue("timesheet_item_id"),
					"person_id" => $timesheet_item->getValue("person_id"),
					"task_id" => $timesheet_item->getValue("task_id"),
					"project_id" => $timesheet_item->getValue("project_id"),
					"timesheet_date" => $timesheet_item->getValue("timesheet_date"),
					"timesheet_hours" => $timesheet_item->getValue("timesheet_hours"),
					"timesheet_notes" => $timesheet_item->getValue("timesheet_notes"),
					"task_name" => $tName["task_name"],
					"project_name" => $pName["project_name"]
				);
			}
		}
		error_log("### " . count($tsItems_JSON));
		error_log(print_r($tsItems_JSON,true));
		*/
		
		/* THIS WAS ALREADY COMMENTED OUT
if ($timesheet_items == 0) {
			$timesheet_items = array();
		}
END ALREADY COMMENTED OUT*/
		//THIS WAS ALREADY COMMENTED OUTerror_log("### " . print_r($timesheet_items));
		
		/*
		$timesheetJSON[] = array(
			"timesheet_id" => $timesheet->getValue("timesheet_id"),
			"timesheet_approved" => $timesheet->getValue("timesheet_approved"),
			"timesheet_submitted" => $timesheet->getValue("timesheet_submitted"),
			"timesheet_start_date" => $timesheet->getValue("timesheet_start_date"),
			"timesheet_end_date" => $timesheet->getValue("timesheet_end_date"),
			"person_id" => $timesheet->getValue("person_id"),
			"timesheet_items" => $tsItems_JSON
		);
		error_log(print_r($timesheetJSON, true));
	}*/

