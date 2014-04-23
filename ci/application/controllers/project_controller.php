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
	
	function project_detail() {
			$project_id = $this->uri->segment(3);
			$data['project'] = $this->Project_model->display_project_by_id($project_id);
			//get the relevent client info for this project
			$client_id = $data['project'][0]->client_id;
			$data['clients'] = $this->Client_model->display_clients_by_id($client_id);
			$data['tasks'] = $this->Task_model->display_tasks_for_project($project_id);
			$data['people'] = $this->Person_model->display_people_for_project($project_id);
			$this->load->view('header_view');
			$this->load->view('projects_detail_view', $data);
		}	
		
	
	function edit_project() {
		$project_id = $this->uri->segment(3);
		$data['project'] = $this->Project_model->display_project_by_id($project_id);
		//get the relevent client info for this project
		$client_id = $data['project'][0]->client_id;
		$data['clients'] = $this->Client_model->display_clients_by_id($client_id);
		$data['all_clients'] = $this->Client_model->display_clients();
		$data['all_tasks'] = $this->Task_model->display_tasks();
		$data['all_people'] = $this->Person_model->display_people();
		$data['tasks'] = $this->Task_model->display_tasks_for_project($project_id);
		$data['people'] = $this->Person_model->display_people_for_project($project_id);
		$this->load->view('header_view');
		$this->load->view('project_edit_view', $data);
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
		//this function needs to be modified to insert people into the project_person and project_task table.
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
		 	$this->Project_model->insert_project_person();
			$this->Project_model->insert_project_task();
			$this->load->view('header_view');
			$this->load->view('project_add_view', $data);
		}
	}

	
	function update_project() {
		//this is the form validation
		//this function needs to be modified to insert people into the project_person and project_task table.
		$project_id = $this->uri->segment(3);
		
		$data = $this->data;
		 $this->form_validation->set_rules('project-name', 'project-name', 'required');
		 $this->form_validation->set_rules('project-code', 'project_code', 'numeric');
		 $this->form_validation->set_rules('project-notes', 'project-notes');
		 $this->form_validation->set_rules('project_invoice_by', 'project_invoice_by');
		 $this->form_validation->set_rules('project_budget_by', 'project_budget_by');
		 $this->form_validation->set_rules('task_id', 'task_id');
		 $this->form_validation->set_rules('client_id', 'client_id');
		 $this->form_validation->set_rules('person_id', 'person_id');
		 $this->form_validation->set_rules('person_ids', 'person_ids');
		if ($project_id) {
			$data['processType'] = "E";
		} else {
			$data['processType'] = "A";
		}
		$data['func'] = $this->input->post('func');
		if ($this->form_validation->run() == FALSE) {
			$data['project'] = $this->Project_model->display_project_by_id($project_id);
			//get the relevent client info for this project
			$client_id = $data['project'][0]->client_id;
			$data['clients'] = $this->Client_model->display_clients_by_id($client_id);
			$data['all_clients'] = $this->Client_model->display_clients();
			$data['all_tasks'] = $this->Task_model->display_tasks();
			$data['all_people'] = $this->Person_model->display_people();
			$data['tasks'] = $this->Task_model->display_tasks_for_project($project_id);
			$data['people'] = $this->Person_model->display_people_for_project($project_id);
			$this->load->view('header_view');
			$this->load->view('project_edit_view', $data);
		} else {
			$this->Project_model->update_project($project_id);
			$this->Project_model->update_project_person($project_id);
			$this->Project_model->update_project_task($project_id);
			$this->Project_model->update_task_hourly_rate();
			$this->Project_model->update_person_hourly_rate();
			$data['project'] = $this->Project_model->display_project_by_id($project_id);
			//get the relevent client info for this project
			$client_id = $data['project'][0]->client_id;
			$data['clients'] = $this->Client_model->display_clients_by_id($client_id);
			$data['all_clients'] = $this->Client_model->display_clients();
			$data['all_tasks'] = $this->Task_model->display_tasks();
			$data['all_people'] = $this->Person_model->display_people();
			$data['tasks'] = $this->Task_model->display_tasks_for_project($project_id);
			$data['people'] = $this->Person_model->display_people_for_project($project_id);
			$this->load->view('header_view');
			$this->load->view('project_edit_view', $data);
		}
	}
}