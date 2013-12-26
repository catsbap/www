<?php

class Search_controller extends CI_Controller {
	var $base;
	var $css;
	
			

   function __construct()
    {
        parent::__construct();
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
		$this->load->view('search_view', $data); //this loads the form  
    }

    function search_client()
    {
         $client_name = $this->input->post('clients');
		 //error_log(print ($client_name));
		 $data['results'] = $this->Search_model->search($this->data['fromdate'],$this->data['todate'],$client_name);
		 //print_r($data);
		 $this->load->view('result_view',$data);
    }
}
