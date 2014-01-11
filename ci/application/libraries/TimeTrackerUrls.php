<?php

class TimeTrackerUrls{


/*
$this->type = $this->uri->segment(6);
		$this->fromdate = $this->uri->segment(3);
		$this->todate = $this->uri->segment(4);
		$this->page = $this->uri->segment(5);
*/
	function generate_client_url($client_id = "", $client_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		//$fromdate = $_GET['fromdate'];
		$fromdate = $obj->uri->segment(3);
		//$todate = $_GET['todate'];
		$todate = $obj->uri->segment(4);
		//$type=$_GET['type'];
		$type = $obj->uri->segment(6);
		//$page = $_GET['page'];
		$page = $obj->uri->segment(5);
		$anchor = anchor("$base/index.php/$controller/$view/$fromdate/$todate/$page/$type/$client_id", "$client_name");
		return $anchor;
	}

	function generate_project_url($project_id = "", $project_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		//$fromdate = $_GET['fromdate'];
		$fromdate = $obj->uri->segment(3);
		//$todate = $_GET['todate'];
		$todate = $obj->uri->segment(4);
		//$type=$_GET['type'];
		$type = $obj->uri->segment(6);
		//$page = $_GET['page'];
		$page = $obj->uri->segment(5);
		$anchor = anchor("$base/index.php/$controller/$view/$fromdate/$todate/$page/$type/$project_id", "$project_name");
		return $anchor;
	}
	
	function generate_task_url($task_id = "", $task_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		//$fromdate = $_GET['fromdate'];
		$fromdate = $obj->uri->segment(3);
		//$todate = $_GET['todate'];
		$todate = $obj->uri->segment(4);
		//$type=$_GET['type'];
		$type = $obj->uri->segment(6);
		//$page = $_GET['page'];
		$page = $obj->uri->segment(5);
		$anchor = anchor("$base/index.php/$controller/$view/$fromdate/$todate/$page/$type/$task_id", "$task_name");
		//$anchor = "<div class='button_$task_id'><a href=#>$task_name</a></div>";
		return $anchor;
	}
	
	function generate_person_url($person_id = "", $person_first_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		//$fromdate = $_GET['fromdate'];
		$fromdate = $obj->uri->segment(3);
		//$todate = $_GET['todate'];
		$todate = $obj->uri->segment(4);
		//$type=$_GET['type'];
		$type = $obj->uri->segment(6);
		//$page = $_GET['page'];
		$page = $obj->uri->segment(5);
		$anchor = anchor("$base/index.php/$controller/$view/$fromdate/$todate/$page/$type/$person_id", "$person_first_name");
		return $anchor;
	}
	
	//this function is called from the projects report.
	//Leave this function here for now, but we may not use it.
	function display_person($task_id, $project_id) {
		$obj =& get_instance();
		$todate = $obj->input->get('todate');
		$fromdate = $obj->input->get('fromdate');
		$type=$_GET['type'];
		$personquery = $obj->Report_model->getPersonsByTask($todate, $fromdate, $task_id);
		
		$rate_temp = $obj->data;
		//print_r($rate_temp['all_data']);
		
		
		$person_url = array();
		foreach ($personquery as $persons) {	
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			$anchored_person_amount = 0;
			foreach($rate_temp['all_data'] as $all_data) {
			//print_r($all_data);
			//print_r($persons);
				if (($persons['person_id'] == $all_data->person_id) && ($persons['task_id'] == $all_data->task_id) && ($persons['project_id'] == $all_data->project_id)) {
					
					$anchored_person_url = $this->generate_person_url($persons['person_id'], $persons['person_first_name'], $obj->data['controller'], $obj->data['view']);
					$running_total_time = $all_data->timesheet_hours + $running_total_time;
					$running_billable_time = $all_data->billable_time + $running_billable_time;
					$running_billable_amount = money_format('%i', $all_data->billable_amount + $running_billable_amount);
				}
			}
						
			$person_url[]['person_url'] = $anchored_person_url;
			$person_url[]['person_total_hours'] = $running_total_time;
			$person_url[]['person_billable_hours'] = $running_billable_time;
			$person_url[]['person_total_amount'] = $running_billable_amount;
		}
		
		$rate = "";
		$person_total_time = "";
		$person_billable_hours = "";
		$person_total_amount = "";

		foreach ($person_url as $key=>$value) {
			foreach ($value as $key=>$value) {
				if ($key == 'person_total_hours') {
					$person_total_time = $person_total_time + $value;
				}
				if ($key == 'person_billable_hours') {
					$person_billable_hours = $person_billable_hours + $value;
				}
				if ($key == 'person_total_rate') {
					$person_total_amount = $person_total_amount + $value;
				}
			}
			$this->data['total_time'] = $person_total_time;
			$this->data['billable_time'] = $person_billable_hours;
			$this->data['billable_amount'] = $person_total_amount;
		}

		//$this->data['person_url'] = $person_url;
		////////just display the person's name.
		foreach($person_url as $person) {
			foreach ($person as $key=>$val) {
				echo "<td><div class='" . $_GET["project_id"] . "'>";
				print_r($val);
				echo "</div></td>";
			}
		}
		$this->data['task_name'] = $obj->Report_model->getTaskName($task_id);
		$data = $this->data;
	}

}
?>