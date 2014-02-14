<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Home extends CI_Controller {
 
    function __construct() {
        parent::__construct(); 
     } 
 
     public function index() {
        $data['title'] = "My Cool Homepage";
        $this->load->view('frontend/home_view', $data);
    }
 
}
?>