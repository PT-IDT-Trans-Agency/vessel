<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/Log.php");
class ApiAgent extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_agent(){
        $object["data"] = $this->db->get("agent")->result();
        echo json_encode($object);
    }

    public function get_master_agent_by_id(){
        $object["data"] = $this->db->get_where("agent", array("agent_code"=>$this->input->get("agent_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_agent(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterAgent");
        $agent_code = $this->input->post("agent_code");
        $agent_name = $this->input->post("agent_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterAgent", "create")){
            $log->activity = "gagal menyimpan data master agent";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("agent", array("agent_code" => $agent_code))->result()) >0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterAgent", "update")){
                    $log->activity = "gagal mengubah data master agent";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("agent", array("agent_name" => $agent_name), array("agent_code"=>$agent_code));
                    $log->activity = "berhasil mengubah data master agent";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("agent", array("agent_name" => $agent_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master agent";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Agent Name already exists";
                } else {
                    $this->db->insert("agent", array("agent_code"=>$agent_code, "agent_name" => $agent_name));
                    $log->activity = "berhasil menyimpan data master agent";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            }
        }
        echo json_encode($object);
    }

    public function delete_data_agent(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterAgent");
        $agent_code = $this->input->post("agent_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterAgent", "delete")){
            $log->activity = "gagal menghapus data master agent";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($agent_code)){
                for ($i=0; $i < count($agent_code); $i++) { 
                    $this->db->delete("agent", array("agent_code"=>$agent_code[$i]));    
                }
            } else {
                $this->db->delete("agent", array("agent_code"=>$agent_code));    
            }
            
            $log->activity = "berhasil menghapus data master agent";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}
?>