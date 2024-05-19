<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class ApiShipper extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_shipper(){
        $object["data"] = $this->db->get("shipper")->result();
        echo json_encode($object);
    }

    public function get_master_shipper_by_id(){
        $object["data"] = $this->db->get_where("shipper", array("shipper_code"=>$this->input->get("shipper_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_shipper(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterShipper");
        $shipper_code = $this->input->post("shipper_code");
        $shipper_name = $this->input->post("shipper_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterShipper", "create")){
            $log->activity = "gagal menyimpan data master shipper";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("shipper", array("shipper_code"=>$shipper_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterShipper", "update")){
                    $log->activity = "gagal menyimpan data master shipper";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("shipper", array(
                        "shipper_name" => $shipper_name
                    ), array(
                        "shipper_code" => $shipper_code
                    ));
                    $log->activity = "berhasil mengubah data master shipper";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("shipper", array("shipper_name" => $shipper_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master shipper";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Shipper Name already exists";
                } else {
                    $this->db->insert("shipper", array("shipper_code"=>$shipper_code, "shipper_name" => $shipper_name));
                    $log->activity = "berhasil menyimpan data master shipper";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
                
            }
        }
        echo json_encode($object);
    }

    public function delete_data_shipper(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterShipper");
        $shipper_code = $this->input->post("shipper_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterShipper", "delete")){
            $log->activity = "gagal menghapus data master shipper";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($shipper_code)){
                for ($i=0; $i < count($shipper_code); $i++) { 
                    $this->db->delete("shipper", array("shipper_code"=>$shipper_code[$i]));
                }
            } else {
                $this->db->delete("shipper", array("shipper_code"=>$shipper_code));
            }
            
            $log->activity = "berhasil menghapus data master shipper";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }

}