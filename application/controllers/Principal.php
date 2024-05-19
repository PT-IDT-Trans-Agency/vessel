<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Principal extends CI_Controller {
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
        $this->load->view("master/principal/index");
    }

    public function delete($principal_code){
        $this->db->delete("principal", array("principal_code"=>$principal_code));
        redirect(base_url("principal"));
    }

    public function add(){
        $this->load->view("master/principal/add");
    }

    public function save(){
        redirect(base_url("principal"));
    }
}