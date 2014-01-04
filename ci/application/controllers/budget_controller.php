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
		//the budget report uses all dates.
		//get all of the hours out of the tables. There is no timeframe, and the query only includes
		//projects that do not have "No Budget" in the project_budget_by field.
		//budget types are project_budget_total_fees (all hours), project_budget_total_hours(billable_hours * hourly_rate)
		//total_budget_hours on person (total hours for person), total_budget_hours on task (total hours for task).
		
		//get all the objects out of the db.
		$data = $this->Budget_model->getAllBudgetedHours();
		foreach ($data as $item) {
			if ($item->project_budget_by == "Total project hours") {
				//get the total hours
				$item->budget = $item->project_budget_total_hours;
				$hours = $item->timesheet_hours;
				//how many hours are left in the budget?
				$item->hours_left = $item->budget - $hours;
				//calculate the percentage of budget and add the total to the object.
				$item->budget_percentage = ($item->budget - $hours) . "%";
				//put the object back into the data array to send to the view.
				$data['budget'][] = $item;
				$item->rate = $hours;
			}
			if ($item->project_budget_by == "Total project fees") {
				//get the total fees, but only billable hours.
				$item->budget = $item->project_budget_total_fees;
				if ($item->project_billable == 1) {
					$hours = $item->timesheet_hours;
				}
				//how many fees are left in the budget?
				//calculate how much money has been spent to date.
				if ($item->project_invoice_by == "Project hourly rate") {
					$item->rate = $hours * $item->project_hourly_rate;
				} elseif ($item->project_invoice_by == "Person hourly rate") {
					$item->rate = $hours * $item->person_hourly_rate;
				} elseif ($item->project_invoice_by == "Task hourly rate") {
					$item->rate = $hours * $item->task_hourly_rate;
				}
				$item->hours_left = $item->budget - $item->rate;
				//calculate the percentage of budget and add the total to the object.
				$item->budget_percentage = ($item->budget - $item->rate)/10 . "%";
				//put the object back into the data array to send to the view.
				$data['budget'][] = $item;
				//print_r($data);
			}
			//QA THESE REPORTS!!
			if ($item->project_budget_by == "Hours per task") {
				$item->budget = $item->task_total_budget_hours;
				$hours = $item->timesheet_hours;
				//how many hours are left in the budget?
				$item->hours_left = $item->budget - $hours;
				//calculate the percentage of budget and add the total to the object.
				$item->budget_percentage = ($item->budget - $hours) . "%";
				//put the object back into the data array to send to the view.
				$data['budget'][] = $item;
				$item->rate = $hours;
			}		
			if ($item->project_budget_by == "Hours per person") {
				$item->budget = $item->person_total_budget_hours;
				$hours = $item->timesheet_hours;
				//how many hours are left in the budget?
				$item->hours_left = $item->budget - $hours;
				//calculate the percentage of budget and add the total to the object.
				$item->budget_percentage = ($item->budget - $hours) . "%";
				//put the object back into the data array to send to the view.
				$data['budget'][] = $item;
				$item->rate = $hours;
			}
		}
		$this->load->view('header_view');
		$this->load->view('budget_view', $data); //this loads the form  
    }
}