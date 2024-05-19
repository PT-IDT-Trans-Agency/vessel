<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/autoload.php");
require_once(APPPATH."libraries/Log.php");
class ApiUser extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_user(){
        $object["data"] = $this->db->get("users")->result();
        echo json_encode($object);
    }

    public function update_data_user(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterUser");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $group_code = $this->input->post("group_code");
        $branch_code = $this->input->post("branch_code");

        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterUser", "create")){
            $log->activity = "gagal menyimpan data master user";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("users", array("username"=>$username, "branch_code" => $branch_code, "type" => $group_code))->result()) >0){
                $object["success"] = "0";
                $object["message"] = "Data sudah ada";
            } else {
                $this->db->insert("users", array(
                    "username" => $username,
                    "password" => md5($password),
                    "branch_code" => $branch_code,
                    "type" => $group_code
                ));
                $log->activity = "berhasil menyimpan data master user";
                $object["success"] = "1";
                $object["message"] = "Saving data is successfully";
            }
        }
        
        
        echo json_encode($object);
    }

    public function change_password(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterUser");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterUser", "update")){
            $log->activity = "gagal mengubah data master user";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $id = $this->input->post("id");
            $new_password = $this->input->post("new_password");
            $this->db->update("users", array(
                "password" => md5($new_password)
            ), array(
                "id" => $id
            ));
            $log->activity = "berhasil mengubah data master user";
            $object["success"] = "1";
            $object["message"] = "Updating data is successfully";
        }
        
        echo json_encode($object);
    }

    public function delete_data_user(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterUser");
        $id = $this->input->post("id");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterUser", "delete")){
            $log->activity = "gagal menghapus data master user";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $this->db->delete("users", array("id" => $id));
            $log->activity = "berhasil menghapus data master user";
            $object["success"] = "1";
            $object["message"] = "Deleting data is successfully";
        }
        
        echo json_encode($object);
    }
}