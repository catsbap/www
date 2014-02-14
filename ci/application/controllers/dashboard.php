<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard extends MY_Controller {
 
    function __construct() {
        parent::__construct(); 
     } 
 
     public function index() {
        $data['title'] = "My Dashboard";
        $this->load->view('backend/dashboard_view', $data);
    }
 
}
?>