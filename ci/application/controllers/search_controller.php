<?php

class Search_controller extends CI_Controller {
	var $base;
	var $css;
		
   function __construct()
    {
        parent::__construct();
		//load form helper
		$this->load->helper('form');	
        $this->load->library('javascript');
		//$this->load->library('jquery');
		$this->data['library_src'] = $this->jquery->script();
		$this->data['script_foot'] = $this->jquery->_compile();
        $this->load->helper('url');
        $url = current_url();
		$this->data['site_url'] = $url . '?' . $_SERVER['QUERY_STRING'];
		$this->load->model('Search_model', '', TRUE);
		//Get the fromdate and todate, in segments 3 & 4.
		$this->data['todate'] = date_format(new DateTime($this->uri->segment(4)), 'Y-m-d');
		$this->data['fromdate'] = date_format(new DateTime($this->uri->segment(3)), 'Y-m-d');
	}
	
	function index() {
		//the top view is always loaded, it is the search form.	
		$data['client_results'] = $this->Search_model->getAllClientTime($this->data['fromdate'], $this->data['todate']);
		$data['project_results'] = $this->Search_model->getAllProjectTime($this->data['fromdate'], $this->data['todate']);
		$data['task_results'] = $this->Search_model->getAllTaskTime($this->data['fromdate'], $this->data['todate']);
		$data['person_results'] = $this->Search_model->getAllPeopleTime($this->data['fromdate'], $this->data['todate']);
		$this->load->view('header_view');
		$this->load->view('search_view', $data); //this loads the form  
    }

    function search_data()
    {
         $client_name = $this->input->post('clients');
		 $project_name = $this->input->post('projects');
		 $task_name = $this->input->post('tasks');
		 $person_name = $this->input->post('people');
		 //lets just do this the long way.
		 if ($client_name == "") {
			 $client_name = "%";
		 }
		 if ($project_name == "") {
			 $project_name = "%";
		 }
		 if ($task_name == "") {
			 $task_name = "%";
		 }
		 if ($person_name == "") {
			 $person_name = "%";
		 }
		 $data['results'] = $this->Search_model->getAllHours($this->data['fromdate'],$this->data['todate'],$client_name, $project_name, $task_name, $person_name);
		 //get out the total hours to display in the UI
		 $running_total = 0;
		 foreach($data['results'] as $row){
		 	$running_total = $running_total + $row->timesheet_hours;
		 }
		 //again, let's do this the long way.
		 if ($client_name == "%") {
			 $client_name = "All Clients";
		 }
		 if ($project_name == "%") {
			 $project_name = "All Projects";
		 }
		 if ($task_name == "%") {
			 $task_name = "All Tasks";
		 }
		 if ($person_name == "%") {
			 $person_name = "All Staff";
		 }
		 $data['running_total'] = $running_total;
		 $data['client_name'] = $client_name;
		 $data['project_name'] = $project_name;
		 $data['task_name'] = $task_name;
		 $data['person_name'] = $person_name;
		 $data['fromdate'] = $this->data['fromdate'];
		 $data['todate'] = $this->data['todate'];
		 $this->load->view('header_view');
		 $this->load->view('result_view',$data);
    }
}