<?php

//this is in a separate file, and only because I was trying to separate it out.
//the code is not as modular in this case as I had hoped, so I did not do this
//to the rest of the reports. This is why this is the only one. The savings just wasn't that great.

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
			$cid = urlencode($clients['client_name']);
			$pid = "";
			$tid = "";
			$did = "";
			$plid = "";
			$client_url[]['client_total_hours'] = "<a href=http://127.0.0.1:8888/time_tracker/ci/index.php/search_controller/search_data/"  . $obj->uri->segment(3) . "/" . $obj->uri->segment(4) . "/0/all_hours/timesheet_date?clients=$cid&projects=$pid&tasks=$tid&department=$did&people=>$running_total_time</a>";
			//$client_url[]['client_total_hours'] = $running_total_time;
			$client_url[]['client_billable_hours'] = $running_billable_time;
			$client_url[]['client_total_amount'] = $running_billable_amount;
		}
		
		$this->data['client_url'] = $client_url;
		return($client_url);
	}
}
	
?>
