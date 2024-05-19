<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class ApiDashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_summary_perbulan_vessel(){
        $tahun = $this->input->get("tahun");
        $branch_code = $this->input->get("branch_code");
        $this->db->select("month(eta) as bulan, count(*) as total_vessel");
        $this->db->from("line_up");
        $data_where["year(eta)"] = $tahun;
        if ($branch_code != ""){
            $data_where["branch_code"] = $branch_code;
        }
        $this->db->where($data_where);
        $this->db->group_by("year(eta), month(eta)");
        $object["data"] = $this->db->get()->result();
        echo json_encode($object);
    }

    public function get_data_filter_vlu(){
        $this->db->select("year(eta) as tahun");
        $this->db->from("line_up");
        $this->db->group_by("year(eta)");
        $object["data_filter_tahun"] = $this->db->get()->result();
        $object["data_branch"] = $this->db->get("branch")->result();
        echo json_encode($object);
    }
}