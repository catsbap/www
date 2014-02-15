<?php

class Person_controller extends CI_Controller {
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
		$this->load->model('Person_model', '', TRUE);
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	
	function index() {

	}
	
	function display_person() {
		//$data['people'] = $this->Person_model->display_people();
		$data['person_types'] = $this->Person_model->display_person_types();
		$data['person_perms'] = $this->Person_model->display_person_perms();
		$this->load->view('header_view');
		$this->load->view('person_add_view', $data);
	}
	
	function view_people() {
		$data['people'] = $this->Person_model->display_people();
		$data['person_perms'] = $this->Person_model->display_person_perms();
		$this->load->view('header_view');
		$this->load->view('person_display_view', $data);
	}

	
	function insert_person() {
		//this is the form validation
		
		//OK, what about this. We use ion_auth and groups to set up the user's permissions. the fields we need that are custom to this app are
		//person_logo_link, hourly rate, department, everything else is in the "USER" table.
		//what if we let the user log in using the user table to update the ion_auth
		 $data = $this->data;
		 $data['success_email'] = "";
		 $this->form_validation->set_rules('person-first-name', 'person-first-name', 'required');
		 $this->form_validation->set_rules('person-last-name', 'person-last-name', 'required');
		 $this->form_validation->set_rules('person-email', 'person-email', 'trim|required|valid_email');
		 $this->form_validation->set_rules('person-department', 'person-department');
		 $this->form_validation->set_rules('person-hourly-rate', 'person-hourly-rate');
		 $this->form_validation->set_rules('person-perms', 'person-perms');
		 $this->form_validation->set_rules('person-type', 'person-type');
		 $this->form_validation->set_rules('dropdown-value', 'dropdown-value');
		 $this->form_validation->set_rules('create_projects', 'create_projects');
		 $this->form_validation->set_rules('view_notes', 'view_notes');
		 $this->form_validation->set_rules('create_invoices', 'create_invoices');

		 /*$this->form_validation->set_rules('client-city', 'client-city');
		 $this->form_validation->set_rules('client-state', 'client-state');
		 $this->form_validation->set_rules('client-address', 'client-address');
		 $this->form_validation->set_rules('contact-name', 'contact-name', 'required');
		 $this->form_validation->set_rules('contact-id', 'contact-id');
		 $this->form_validation->set_rules('contact-officePhone', 'contact-officePhone');
		 $this->form_validation->set_rules('contact-mobilePhone', 'contact-mobilePhone');
		 $this->form_validation->set_rules('contact-email', 'contact-email');
		 $this->form_validation->set_rules('contact-fax', 'contact-fax');
		 $this->form_validation->set_rules('client_logo_link', 'client_logo_link');
		 */
		 
		 if ($this->form_validation->run() == FALSE) {
			 $this->load->view('header_view');
			 $this->load->view('person_add_view', $data);
		 } else {	
			$this->Person_model->insert_person($data);
			$this->load->library('email');
			//configure mail when this project is set up on remote server
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'smtp.gmail.com',
				'smtp_port' => 587,
				'smtp_user' => '****',
				'smtp_pass' => '****',
				'charset'   => 'iso-8859-1',
				'starttls'  => true,
				'validate' => false,
			);

		   $this->load->library('email', $config);
		   $this->email->set_newline("\r\n");

		   $this->email->from('catsbap@gmail.com', 'Dinuka Thilanga');
		   $this->email->to('catsbap@gmail.com');
		   $this->email->subject('Email Test');
		   $this->email->message('Testing the email class.');
		
		   //$this->email->send();
		
		   //if($this->email->send())	{
		   		$data['success_email'] = "An email has been sent to this person with instructions for how to log in.";
			//}else{
			//	show_error($this->email->print_debugger());
			//}		   
		 		$this->load->view('header_view');
		 		$this->load->view('person_add_view', $data);
			}
	}
	
	function view_clients() {
			$data['clients'] = $this->Client_model->display_clients();
			$this->load->view('header_view');
			$this->load->view('client_display_view', $data);
	}
	
	function edit_person() {
		$person_id = $this->uri->segment(3);
		$data['person'] = $this->Person_model->display_people_by_id($person_id);
		$data['person_projects'] = $this->Person_model->display_projects_for_person($person_id);
		$data['all_projects'] = $this->Person_model->display_projects();
		$this->load->view('header_view');
		$this->load->view('person_edit_view', $data);
	}
	
	/*
	function client_detail() {
		$client_id = $this->uri->segment(3);
		$data['client'] = $this->Client_model->display_clients_by_id($client_id);
		$data['contact'] = $this->Client_model->display_contacts_by_client_id($client_id);
		$this->load->view('header_view');
		$this->load->view('client_detail_view', $data);
	}
	*/
	function update_person() {
		//this is the form validation
		$data = $this->data;
		$person_id = $this->uri->segment(3);

		 $data['success_email'] = "";
		 $data['person'] = $this->Person_model->display_people_by_id($person_id);
		 $data['person_projects'] = $this->Person_model->display_projects_for_person($person_id);
		 $data['all_projects'] = $this->Person_model->display_projects();
		 $this->form_validation->set_rules('person-first-name', 'person-first-name', 'required');
		 $this->form_validation->set_rules('person-last-name', 'person-last-name', 'required');
		 $this->form_validation->set_rules('person-email', 'person-email', 'trim|required|valid_email');
		 $this->form_validation->set_rules('person-department', 'person-department');
		 $this->form_validation->set_rules('person-hourly-rate', 'person-hourly-rate');
		 $this->form_validation->set_rules('person-perms', 'person-perms');
		 $this->form_validation->set_rules('person-type', 'person-type');
		 $this->form_validation->set_rules('dropdown-value', 'dropdown-value');
		 $this->form_validation->set_rules('create_projects', 'create_projects');
		 $this->form_validation->set_rules('view_notes', 'view_notes');
		 $this->form_validation->set_rules('create_invoices', 'create_invoices');


				 
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('header_view');
			$this->load->view('person_edit_view', $data);
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
				//echo 'Bad request!';
				//print_r($_FILES["image"]["error"]);
				//print_r($_FILES["image"]);
			}	
			$this->Person_model->update_person($person_id);
			$this->load->view('header_view');
			$this->load->view('person_edit_view', $data);
		}
	}
}