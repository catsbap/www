<?php

class Client_rollup{
	function client_rollup()
	{
		$obj =& get_instance();
		$obj->load->model('Report_model', '', TRUE);
		$this->data['controller'] = "report";
		$this->data['view'] = "client";
		$client_url = array();
		$client_url[]['client_billable_rate'] = array();
		$client_url[]['client_billable_time'] = array();
		$client_url[]['client_total_time'] = array();
		//ROLL UP all data at the client level and display by client ID.
		$clientquery = $obj->Report_model->getClientHours($obj->todate, $obj->fromdate);
		foreach ($clientquery as $clients) {			
			$running_total_time = 0;
			$running_billable_time = 0;
			$running_billable_amount = 0;
			foreach($obj->data['all_data'] as $data) {
				if ($clients['client_id'] == $data->client_id) {		
					
					$anchored_client_url = $obj->timetrackerurls->generate_client_url($clients['client_id'], $clients['client_name'], $this->data['controller'], $this->data['view']);
					
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
		return($client_url);
	}
}
	
?>
