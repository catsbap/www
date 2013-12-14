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

}
?>