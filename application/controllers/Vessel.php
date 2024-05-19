<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class Vessel extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library("Auth");
        
        if ($this->session->user_name == null) {
            redirect(base_url());
        }
    }

    public function index(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterVessel");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterVessel", "list")){
            $log->activity = "gagal membuka form list";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $log->activity = "membuka form list";
            $log->record_log();
            $this->load->view("master/vessel");
        }
        
    }
}