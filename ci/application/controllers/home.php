<?php
//this is the main controler for this application.
if( ! defined('BASEPATH')) exit('No direct script access allowed');


//this is extending MY_Controller, not CI_Controller.
class Home extends MY_Controller {
 
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
        if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
     } 
 
     public function index() {
		$data['title'] = "Welcome to The Time Tracker System";
        $this->load->view('header_view', $data);
        $this->load->view('bootstrap_view', $data);
    }
 
}
?>