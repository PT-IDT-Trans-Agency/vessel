<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/Log.php");
class ApiBuyer extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_buyer(){
        $object["data"] = $this->db->get("buyer")->result();
        echo json_encode($object);
    }

    public function get_master_buyer_by_id(){
        $object["data"] = $this->db->get_where("buyer", array("buyer_code"=>$this->input->get("buyer_code")))->result();
        echo json_encode($object);
    }
    
    public function update_data_buyer(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterBuyer");
        $buyer_code = $this->input->post("buyer_code");
        $buyer_name = $this->input->post("buyer_name");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterBuyer", "create")){
            $log->activity = "gagal menyimpan data master buyer";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("buyer", array("buyer_code"=>$buyer_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterBuyer", "update")){
                    $log->activity = "gagal mengubah data master buyer";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("buyer", array(
                        "buyer_name" => $buyer_name
                    ), array(
                        "buyer_code" => $buyer_code
                    ));
                    $log->activity = "berhasil mengubah data master buyer";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                if (count($this->db->get_where("buyer", array("buyer_name" => $buyer_name))->result()) > 0){
                    $log->activity = "gagal mengubah data master buyer";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "Buyer Name already exists";
                } else {
                    $this->db->insert("buyer", array("buyer_code"=>$buyer_code, "buyer_name" => $buyer_name));
                    $log->activity = "berhasil menyimpan data master agent";
                    $log->record_log();
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
                
            }
        }
        echo json_encode($object);
    }

    public function delete_data_buyer(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterBuyer");
        $buyer_code = $this->input->post("buyer_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterBuyer", "delete")){
            $log->activity = "gagal menghapus data master buyer";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (is_array($buyer_code)){
                for ($i=0; $i < count($buyer_code); $i++) { 
                    $this->db->delete("buyer", array("buyer_code"=>$buyer_code[$i]));
                }
            } else {
                $this->db->delete("buyer", array("buyer_code"=>$buyer_code));
            }
            
            $log->activity = "berhasil menghapus data master buyer";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}
?>