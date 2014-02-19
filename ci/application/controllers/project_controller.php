<?php

class Project_controller extends CI_Controller {
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
		$this->load->model('Project_model', '', TRUE);
		$this->load->model('Client_model', '', TRUE);
		$this->load->model('Task_model', '', TRUE);
		$this->load->model('Person_model', '', TRUE);
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}
	}
	
	
	function index() {
	
	}
	
	function display_projects() {
			$data['projects'] = $this->Project_model->display_projects_and_clients();
			$data['clients'] = $this->Client_model->display_clients();
			$data['tasks'] = $this->Task_model->display_tasks();
			$data['people'] = $this->Person_model->display_people();
			$this->load->view('header_view');
			$this->load->view('projects_view', $data);
		}
	
	function view_project() {
		$data['projects'] = $this->Project_model->display_projects_and_clients();
		$data['clients'] = $this->Client_model->display_clients();
		$data['tasks'] = $this->Task_model->display_tasks();
		$data['people'] = $this->Person_model->display_people();
		$this->load->view('header_view');
		$this->load->view('project_add_view', $data);
	}
		
	function insert_project() {
		//this is the form validation
		 $data = $this->data;
		 $this->form_validation->set_rules('project-name', 'project-name', 'required|is_unique[client.client_name]');
		 $this->form_validation->set_rules('project-code', 'project_code', 'numeric');
		 $this->form_validation->set_rules('project-notes', 'project-notes');
		 $this->form_validation->set_rules('project_invoice_by', 'project_invoice_by');
		 $this->form_validation->set_rules('project_budget_by', 'project_budget_by');
		 $this->form_validation->set_rules('task_id', 'task_id');
		 $this->form_validation->set_rules('client_ids', 'client_ids');
		 $this->form_validation->set_rules('person_id', 'person_id');
		 $this->form_validation->set_rules('person_ids', 'person_ids');
		 //$this->form_validation->set_rules('person_list', 'person_list');

		 
		 if ($this->form_validation->run() == FALSE) {
		 	$data['projects'] = $this->Project_model->display_projects_and_clients();
		 	$data['clients'] = $this->Client_model->display_clients();
			$data['tasks'] = $this->Task_model->display_tasks();
			$data['people'] = $this->Person_model->display_people();
			 $this->load->view('header_view');
			 $this->load->view('project_add_view', $data);
		 } else {
		 	$data['projects'] = $this->Project_model->display_projects_and_clients();
		 	$data['clients'] = $this->Client_model->display_clients();
			$data['tasks'] = $this->Task_model->display_tasks();
			$data['people'] = $this->Person_model->display_people();
		 	$this->Project_model->insert_project($data);
			$this->load->view('header_view');
			$this->load->view('project_add_view', $data);
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