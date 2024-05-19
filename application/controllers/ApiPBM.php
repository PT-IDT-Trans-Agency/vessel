<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class ApiPBM extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_pbm(){
        $object["data"] = $this->db->get("pbm")->result();
        echo json_encode($object);
    }

    public function get_master_pbm_by_id(){
        $object["data"] = $this->db->get_where("pbm", array("pbm_code"=>$this->input->get("pbm_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_pbm(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterPBM");
        $pbm_code = $this->input->post("pbm_code");
        $pbm_name = $this->input->post("pbm_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPBM", "create")){
            $log->activity = "gagal menyimpan data master perusahaan bongkar muat";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("pbm", array("pbm_code"=>$pbm_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPBM", "update")){
                    $log->activity = "gagal menyimpan data master perusahaan bongkar muat";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("pbm", array(
                        "pbm_name" => $pbm_name
                    ), array(
                        "pbm_code" => $pbm_code
                    ));
                    $log->activity = "berhasil mengubah data master perusahaan bongkar muat";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("pbm", array("pbm_name" => $pbm_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master perusahaan bongkar muat";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "PBM Name already exists";
                } else {
                    $this->db->insert("pbm", array("pbm_code"=>$pbm_code, "pbm_name" => $pbm_name));
                    $log->activity = "berhasil menyimpan data master perusahaan bongkar muat";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
                
            }
        }
        echo json_encode($object);
    }

    public function delete_data_pbm(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterPBM");
        $pbm_code = $this->input->post("pbm_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPBM", "delete")){
            $log->activity = "gagal menghapus data master perusahaan bongkar muat";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($pbm_code)){
                for ($i=0; $i < count($pbm_code); $i++) { 
                    $this->db->delete("pbm", array("pbm_code"=>$pbm_code[$i]));
                }
            } else {
                $this->db->delete("pbm", array("pbm_code"=>$pbm_code));
            }
            
            $log->activity = "berhasil menghapus data master perusahaan bongkar muat";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}
?>