<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');

        if ($this->session->user_name == null) {
            redirect(base_url());
        }
    }

    public function index(){
        $this->load->view("dashboard");
    }

}