<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Service extends CI_Controller {
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
        $this->data_view["data_category"] = $this->db->get("service_category")->result();
        $this->load->view("master/service/index", $this->data_view);
    }

    public function delete($service_code){
        $this->db->delete("service", array("service_code"=>$service_code));
        redirect(base_url("service"));
    }

    public function add(){
        $this->data_view["data_category"] = $this->db->get("service_category")->result();
        $this->load->view("master/service/add",$this->data_view);
    }

    public function save(){
        redirect(base_url("service"));
    }
}