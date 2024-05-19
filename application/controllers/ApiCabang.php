<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/Log.php");
class ApiCabang extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_cabang(){
        $object["data"] = $this->db->get("branch")->result();
        echo json_encode($object);
    }

    public function get_cabang(){
        $branch_code = $this->input->get("branch_code");
        foreach ($this->db->get_where("branch", array("branch_code"=>$branch_code))->result() as $branch) {
            $object["branch_code"] = $branch->branch_code;
            $object["branch_name"] = $branch->branch_name;
            $object["branch_port"] = $this->db->get_where("branch_port", array("branch_code"=>$branch_code))->result();
        }
        echo json_encode($object);
    }

    public function insert_cabang(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterCabang");
        $branch_code = $this->input->post("branch_code");
        $branch_name = $this->input->post("branch_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterCabang", "create")){
            $log->activity = "gagal menyimpan data master cabang (branch code : ". $branch_code.")";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("branch", array("branch_code" => $branch_code))->result()) >0){
                $object["success"] = 0;
                $object["message"] = "Branch code is duplicate";
            } else {
                $this->db->insert("branch", array(
                    "branch_code" => $branch_code,
                    "branch_name" => $branch_name
                ));
                $log->activity = "berhasil menyimpan data master cabang (branch code : ". $branch_code.")";
                $log->record_log();
                $object["success"] = 1;
                $object["message"] = "Saving data is successfully";
            }
        }
        echo json_encode($object);
    }

    public function delete_cabang(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterCabang");
        $branch_code = $this->input->post("branch_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterCabang", "delete")){
            $log->activity = "gagal menghapus data master cabang (branch code : ". $branch_code.")";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
            echo json_encode($object);
        } else {
            $this->db->delete("branch", array("branch_code"=>$branch_code));
            $log->activity = "berhasil menghapus data master cabang (branch code : ". $branch_code.")";
            $log->record_log();
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
            echo json_encode($object);
        }
    }

}