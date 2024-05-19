<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class User extends CI_Controller {

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
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterUser");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterUser", "list")){
            $log->activity = "gagal membuka form user";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $this->data_view["data_group"] = $this->db->get("group")->result();
            $this->data_view["data_branch"] = $this->db->get("branch")->result();
            $this->load->view("master/user",$this->data_view);
        }
    }

}