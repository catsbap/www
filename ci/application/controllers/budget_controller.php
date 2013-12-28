<?php

class Budget_controller extends CI_Controller {
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
		$this->load->model('Budget_model', '', TRUE);
		//Get the fromdate and todate, in segments 3 & 4.
		$this->data['todate'] = date_format(new DateTime($this->uri->segment(4)), 'Y-m-d');
		$this->data['fromdate'] = date_format(new DateTime($this->uri->segment(3)), 'Y-m-d');
	}
	
	function index() {
		//the top view is always loaded, it is the search form.	
		$data['results'] = $this->Budget_model->getAllBudgetedHours($this->data['fromdate'], $this->data['todate']);
		error_log(print_r($data,true));
		$this->load->view('header_view');
		$this->load->view('budget_view', $data); //this loads the form  
    }
}
