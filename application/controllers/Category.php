<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends CI_Controller {
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
        $this->load->view("master/service-category/index");
    }

    public function delete($category_code){
        $this->db->delete("service_category", array("category_code"=>$category_code));
        redirect(base_url("category"));
    }

    public function add(){
        $this->load->view("master/service-category/add");
    }

    public function save(){
        redirect(base_url("category"));
    }
}