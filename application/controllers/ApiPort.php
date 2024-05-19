<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class ApiPort extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_port(){
        $object["data"] = $this->db->get("port")->result();
        echo json_encode($object);
    }

    public function get_master_port_by_id(){
        $object["data"] = $this->db->get_where("port", array("port_code"=>$this->input->get("port_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_port(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterPort");
        $port_code = $this->input->post("port_code");
        $port_name = $this->input->post("port_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPort", "create")){
            $log->activity = "gagal menyimpan data master port";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("port", array("port_code"=>$port_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPort", "update")){
                    $log->activity = "gagal mengubah data master port";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("port", array(
                        "port_name" => $port_name
                    ), array(
                        "port_code" => $port_code
                    ));
                    $log->activity = "berhasil mengubah data master port";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("port", array("port_name" => $port_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master port";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Port Name already exists";
                } else {
                    $this->db->insert("port", array("port_code"=>$port_code, "port_name" => $port_name));
                    $log->activity = "berhasil menyimpan data master port";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            }
        }
        echo json_encode($object);
    }

    public function delete_data_port(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterPort");
        $port_code = $this->input->post("port_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterPort", "delete")){
            $log->activity = "gagal menghapus data master port";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($port_code)){
                for ($i=0; $i < count($port_code); $i++) { 
                    $this->db->delete("port", array("port_code"=>$port_code[$i]));
                }
            } else {
                $this->db->delete("port", array("port_code"=>$port_code));
            }
            
            $log->activity = "berhasil menghapus data master port";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}
?>