<?php

class Timesheet_controller extends CI_Controller {
	//JSON ENCODE FUNCTIONS HAVE BEEN PLACED IN DATA_CONTROLLER, CHECK THERE FOR JSON DATA USED
	//BY JQUERY.
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
		$this->load->model('Timesheet_model', '', TRUE);
		$this->load->model('Timesheet_item_model', '', TRUE);
		$this->load->model('Project_person_model', '', TRUE);
		$this->load->model('Project_task_model', '', TRUE);
		$this->load->model('Client_model', '', TRUE);
		$this->load->model('Project_model', '', TRUE);
		$this->load->model('Person_model', '', TRUE);

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	
	function index() {
	
	}
	
	function display_timesheet() {
		//get out the logged in user based on their email from ion_auth
		//this is to allow login in dev. remove beyond.
		$this_user = $this->session->userdata( 'email' );
		if ($this_user == "admin@admin.com") {
			$this_user = "catsbap@gmail.com";
		}
		if ($this_user) {
			if ($this_user == "catsbap@gmail.com") {
				$data['person'] = 198;	
			} else {
				$data['person'] = $this->Person_model->display_person_by_email($this_user);
			}
		} else {
			echo("Something is wrong here...this person is not logged in and you shouldn't be seeing this, timesheet.php.");
			exit();
		}
		$task_id = $this->input->post('task_id');
		if ($task_id) {
			$data['processType'] = "E";
			$data['func'] = $this->input->post('func');
			//error_log("here is func above" . $this->input->post('func'));
			$data['projects'] = $this->Project_person_model->display_projects($project_id);
			error_log("Here is the project");
			error_log(print_r($data['projects'], true));
			
			$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');
			//$this->Task_model->update_task($task_id);
		} else {
			$data['processType'] = "A";
			$data['func'] = $this->input->post('func');
			error_log("here is func" . $this->input->post('func'));
			$data['projects'] = $this->Project_person_model->display_projects($data['person']);
			error_log("Here is the project");
			error_log(print_r($data['projects'], true));
			//$data['tasks'] = $this->Task_model->display_tasks();
			$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');
			//only update the task if the user is adding the task,
			//so there is a value in the func variable in the post.
			if ($this->input->post('func')) {
				//$this->Task_model->insert_task();
			}
			$this->load->view('header_view');
			$this->load->view('timesheet_view', $data);
		}
	}	
	
	function update_timesheet() {
		$task_hourly_rate = $this->input->post('task-hourly-rate');
		$task_bill_by_default = $this->input->post('task-bill-by-default');
		$task_common = $this->input->post('task-common');
		$task_archived = $this->input->post('task-archived');
		$task_id = $this->input->post('task_id');
		if ($task_id) {
			$data['processType'] = "E";
		} else {
			$data['processType'] = "A";
		}
		echo $this->input->post('task_name');
		$data['func'] = $this->input->post('func');
		$data['tasks'] = $this->Task_model->display_tasks();
		 $this->form_validation->set_rules('task-id', 'task-id');
		 $this->form_validation->set_rules('task-name', 'task-name');
		 $this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
		 $this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
		 $this->form_validation->set_rules('task-common', 'task-common');
		 $this->form_validation->set_rules('task-archived', 'task-archived');
		 
		 		 
		$this->Task_model->update_task($task_id);
		$this->load->view('header_view');
		$this->load->view('tasks_view', $data);
	}

}