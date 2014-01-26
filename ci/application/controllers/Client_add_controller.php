<?php

class Client_add_controller extends CI_Controller {
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
		$this->load->model('Client_model', '', TRUE);
	}
	
	
	function index() {
		$client_name = $this->input->post('client_name');
		$data['client_id'] = $this->Client_model->getClientId($client_name);
		$this->load->view('header_view');
		$this->load->view('client_add_view', $data);

	}
	
	function insert_client() {
		//this is the form validation
		 $data = $this->data;
		 $this->form_validation->set_rules('client-name', 'client-name', 'required|is_unique[client.client_name]');
		 $this->form_validation->set_rules('client-phone', 'client-phone', 'numeric');
		 $this->form_validation->set_rules('client-email', 'client-email', 'trim|required|valid_email');
		 $this->form_validation->set_rules('client-fax', 'client-fax', 'numeric');
		 $this->form_validation->set_rules('client-address', 'client-address');
		 $this->form_validation->set_rules('contact-name', 'contact-name', 'required');
		 $this->form_validation->set_rules('client_logo_link', 'client_logo_link');
		 
		 if ($this->form_validation->run() == FALSE) {
			 $this->load->view('header_view');
			 $this->load->view('client_add_view', $data);
		 } else {
		 	$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		 	$max_size = 20000 * 1024; // max file size
		 	$path = '/uploads/'; // upload directory

		  		
		  	//this should use the array value set above.
		  	//set up the image and check it here. 
		  	//this is not as robust as it should be!
			if (isset($_FILES["image"]) and $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
				if ( $_FILES["image"]["type"] != "image/jpeg") {
					error_log("File type isn't correct.");
				} elseif ( !move_uploaded_file($_FILES["image"]["tmp_name"], "/Applications/MAMP/htdocs/time_tracker/ci/uploads/" . basename($_FILES["image"]["name"]))) {
					error_log("Something went wrong uploading the file.");
				} else {
					$_POST["client_logo_link"] = $_FILES["image"]["name"];
				}
			} else {
				echo 'Bad request!';
				echo $_FILES["image"]["error"];
				echo $_FILES["image"];
			}	
			$this->Client_model->insert_client($data);
			$this->load->view('header_view');
			$this->load->view('client_add_view', $data);
		}
	}
}


