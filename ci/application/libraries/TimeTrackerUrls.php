<?php

class TimeTrackerUrls{
	function generate_client_url($client_id = "", $client_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$kind = 'week';
		$page = $_GET['page'];
		$anchor = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&client_id=$client_id&page=$page", "$client_name");
		return $anchor;
	}

	function generate_project_url($project_id = "", $project_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$kind = 'week';
		$page = $_GET['page'];
		$anchor = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&project_id=$project_id&page=$page", "$project_name");
		return $anchor;
	}
	
	function generate_task_url($task_id = "", $task_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$kind = 'week';
		$page = $_GET['page'];
		$anchor = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&task_id=$task_id&page=$page", "$task_name");
		return $anchor;
	}
	
	function generate_person_url($person_id = "", $person_first_name="", $controller, $view) {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$kind = 'week';
		$page = $_GET['page'];
		$anchor = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&person_id=$person_id&page=$page", "$person_first_name");
		return $anchor;
	}
	
	function display_person($task_id) {
		$obj =& get_instance();
		$todate = $obj->input->get('todate');
		$fromdate = $obj->input->get('fromdate');
		$personquery = $obj->Report_model->getPersonsByTask($todate, $fromdate, $task_id);
		
		$rate_temp = $obj->data["rate_temp"];
		
		$person_url = array();
		foreach ($personquery as $persons) {	
			$running_total_time = '';
			$running_billable_time = '';
			$running_billable_rate = '';
			$anchored_person_url = '';
			foreach($rate_temp['person_id'] as $key=>$val) {
				if (($persons['person_id'] == $rate_temp['person_id'][$key]) && ($persons['task_id'] == $rate_temp['task_id'][$key]) && ($persons['project_id'] == $rate_temp['project_id'][$key])) {
					
					$anchored_person_url = $this->generate_person_url($persons['person_id'], $persons['person_first_name'], $obj->data['controller'], $obj->data['view']);
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

		//$this->data['person_url'] = $person_url;
		////////just display the person's name.
		foreach($person_url as $person) {
			foreach ($person as $key=>$val) {
				echo "<td><div id='" . $persons['person_id'] . "'>";
				print_r($val);
				echo "</div></td>";
			}
		}
		$this->data['task_name'] = $obj->Report_model->getTaskName($task_id);
		$data = $this->data;
	}

}
?>