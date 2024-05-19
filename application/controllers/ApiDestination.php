<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/Log.php");
class ApiDestination extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_destination(){
        $object["data"] = $this->db->get("destination")->result();
        echo json_encode($object);
    }

    public function get_master_destination_by_id(){
        $object["data"] = $this->db->get_where("destination", array("destination_code"=>$this->input->get("destination_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_destination(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterDestination");
        $destination_code = $this->input->post("destination_code");
        $destination_name = $this->input->post("destination_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterDestination", "create")){
            $log->activity = "gagal menyimpan data master destination";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("destination", array("destination_code"=>$destination_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterDestination", "update")){
                    $log->activity = "gagal mengubah data master destination";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("destination", array(
                        "destination_name" => $destination_name
                    ), array(
                        "destination_code" => $destination_code
                    ));
                    $log->activity = "berhasil mengubah data master destination";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("destination", array("destination_name" => $destination_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master destination";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Destination Name already exists";
                } else {
                    $this->db->insert("destination", array("destination_code"=>$destination_code, "destination_name" => $destination_name));
                    $log->activity = "berhasil menyimpan data master destination";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
                
            }
        }
        echo json_encode($object);
    }

    public function delete_data_destination(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterDestination");
        $destination_code = $this->input->post("destination_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterDestination", "delete")){
            $log->activity = "gagal menghapus data master destination";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($destination_code)){
                for ($i=0; $i < count($destination_code); $i++) { 
                    $this->db->delete("destination", array("destination_code"=>$destination_code[$i]));
                }
            } else {
                $this->db->delete("destination", array("destination_code"=>$destination_code));
            }
            
            $log->activity = "berhasil menghapus data master destination";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}
?>