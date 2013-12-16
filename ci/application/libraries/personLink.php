<?php

//function task_report($task_id, $rate_temp) {
		//FIGURE OUT HOW TO GET THE CLIENT TO SHOW UP JUST AS A DROPDOWN.
        /*$CI =& get_instance();
		$project_id = $this->input->get('project_id');
		$todate = $this->input->get('todate');
		$fromdate = $this->input->get('fromdate');
		$rate_temp = $this->data['rate_temp'];
		$this->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report_controller";
		$this->data['view'] = "person_report";
		*/
		echo "ERT";
		//print_r($this->data['task_id_client']);
		//echo $task_id;
		//get all of the persons for this task for the given project id, in the URL.
		//$personquery = $this->Report_model->getPersonsByTask($this->todate, $this->fromdate, $task_id);
		/*$person_url = array();
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
		print_r($data);
		//$this->load->view('report_task_view', $data);
	//}
	*/
	//echo $project_id;
	return 0;
?>
