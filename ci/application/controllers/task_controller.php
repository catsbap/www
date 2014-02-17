<?php

class Task_controller extends CI_Controller {
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
		$this->load->model('Task_model', '', TRUE);
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	
	function index() {
	
	}
	
	function display_task() {
		//$data['people'] = $this->Person_model->display_people();
		//$data['person_types'] = $this->Task_model->display_person_types();
		$task_id = $this->input->post('task_id');
		if ($task_id) {
			$data['processType'] = "E";
			$data['func'] = $this->input->post('func');
			//error_log("here is func above" . $this->input->post('func'));
			$data['tasks'] = $this->Task_model->display_tasks();
			
			$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');
			$this->Task_model->update_task($task_id);
		} else {
			$data['processType'] = "A";
			$data['func'] = $this->input->post('func');
			error_log("here is func" . $this->input->post('func'));
			$data['tasks'] = $this->Task_model->display_tasks();
			$this->form_validation->set_rules('task-id', 'task-id');
			$this->form_validation->set_rules('task-name', 'task-name');
			$this->form_validation->set_rules('task-hourly-rate', 'task-hourly-rate');
			$this->form_validation->set_rules('task-bill-by-default', 'task-bill-by-default');
			$this->form_validation->set_rules('task-common', 'task-common');
			$this->form_validation->set_rules('task-archived', 'task-archived');
			//only update the task if the user is adding the task,
			//so there is a value in the func variable in the post.
			if ($this->input->post('func')) {
				$this->Task_model->insert_task();
			}
			$this->load->view('header_view');
			$this->load->view('tasks_view', $data);
		}
	}	
	
	function update_task() {
		//this is the form validation
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