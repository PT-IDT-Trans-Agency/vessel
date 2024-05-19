<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class ApiVessel extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_vessel(){
        $object["data"] = $this->db->get("vessel")->result();
        echo json_encode($object);
    }
    public function get_master_vessel_by_id(){
        $object["data"] = $this->db->get_where("vessel", array("vessel_code"=>$this->input->get("vessel_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_vessel(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterVessel");
        $vessel_code = $this->input->post("vessel_code");
        $vessel_name = $this->input->post("vessel_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterVessel", "create")){
            $log->activity = "gagal menyimpan data master vessel";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("vessel", array("vessel_code"=>$vessel_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterVessel", "update")){
                    $log->activity = "gagal mengubah data master vessel";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("vessel", array(
                        "vessel_name" => $vessel_name
                    ), array(
                        "vessel_code" => $vessel_code
                    ));
                    $log->activity = "berhasil mengubah data master vessel";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("vessel", array("vessel_name" => $vessel_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master vessel";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Vessel Name already exists";
                } else {
                    $this->db->insert("vessel", array("vessel_code"=>$vessel_code, "vessel_name" => $vessel_name));
                    $log->activity = "berhasil menyimpan data master vessel";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
                
            }
        }
        echo json_encode($object);
    }

    public function delete_data_vessel(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterVessel");
        $vessel_code = $this->input->post("vessel_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterVessel", "delete")){
            $log->activity = "gagal menghapus data master vessel";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($vessel_code)){
                for ($i=0; $i < count($vessel_code); $i++) { 
                    $this->db->delete("vessel", array("vessel_code"=>$vessel_code[$i])); 
                }
            } else {
                $this->db->delete("vessel", array("vessel_code"=>$vessel_code));
            }
            
            $log->activity = "berhasil menghapus data master vessel";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}