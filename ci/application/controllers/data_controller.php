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
		}
		//build other functions here as they come up.
	}
}