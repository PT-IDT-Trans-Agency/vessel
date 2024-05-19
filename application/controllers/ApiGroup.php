<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/autoload.php");
require_once(APPPATH."libraries/Log.php");
class ApiGroup extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_group(){
        $object["data"] = $this->db->get("group")->result();
        echo json_encode($object);
    }

    public function gets_group_akses(){
        $group_code = $this->input->get("group_code");
        $this->db->select("menu.menu_name, group_access.*");
        $this->db->from("group_access");
        $this->db->join("menu", "group_access.menu_code=menu.menu_code");
        $this->db->where("group_access.group_code", $group_code);
        $object["data"] = $this->db->get()->result();
        echo json_encode($object);
    }

    public function get_group_akses(){
        $recnum = $this->input->get("recnum");
        $this->db->select("menu.menu_name, group.group_name, group_access.*");
        $this->db->from("group_access");
        $this->db->join("menu", "group_access.menu_code=menu.menu_code");
        $this->db->join("group", "group_access.group_code=group.group_code");
        $this->db->where("group_access.recnum", $recnum);
        $object["data"] = $this->db->get()->result();
        echo json_encode($object);
    }

    public function update_group(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterGroup");
        $branch_code = $this->input->post("branch_code");
        $group_code = $this->input->post("group_code");
        $group_name = $this->input->post("group_name");

        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterGroup", "create")){
            $log->activity = "gagal menyimpan data master group";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("group", array("group_code"=>$branch_code.".".$group_code))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterGroup", "update")){
                    $log->activity = "gagal mengubah data master group";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $this->db->update("group",array(
                        "group_name" => $group_name
                    ), array(
                        "group_code" => $branch_code.".".$group_code
                    ));
                    $log->activity = "berhasil mengubah data master group";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                $this->db->insert("group", array(
                    "group_code" => $branch_code.".".$group_code,
                    "group_name" => $group_name
                ));
                foreach ($this->db->get("menu")->result() as $menu) {
                    $this->db->insert("group_access", array(
                        "group_code" => $branch_code.".".$group_code,
                        "menu_code" => $menu->menu_code,
                        "list" => 0,
                        "create" => 0,
                        "update" => 0,
                        "delete" => 0,
                        "approve" => 0,
                        "print" => 0
                    ));
                }
                $log->activity = "berhasil menyimpan data master group";
                $log->record_log();
                $object["success"] = 1;
                $object["message"] = "Saving data is successfully";
            }
        }
        echo json_encode($object);
    }

    public function update_konfigurasi_menu(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterGroup");
        $recnum = $this->input->post("recnum");
        $list = $this->input->post("list");
        $create = $this->input->post("create");
        $update = $this->input->post("update");
        $delete = $this->input->post("delete");
        $approve = $this->input->post("approve");
        $print = $this->input->post("print");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterGroup", "update")){
            $log->activity = "gagal mengubah data master group";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $this->db->update("group_access", array(
                "list" => $list,
                "create" => $create,
                "update" => $update,
                "delete" => $delete,
                "approve" => $approve,
                "print" => $print,
            ), array(
                "recnum" => $recnum
            ));
            $log->activity = "berhasil mengubah data master group";
            $log->record_log();
            $object["success"] = 1;
            $object["message"] = "Saving data is successfully";
        }
        echo json_encode($object);
    }

    public function delete_group(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterGroup");
        $branch_code = $this->input->post("branch_code");
        $group_code = $this->input->post("group_code");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_MasterGroup", "delete")){
            $log->activity = "gagal menghapus data master group";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $this->db->delete("group",array(
                "group_code" => $branch_code.".".$group_code
            ));
    
            $this->db->delete("group_access",array(
                "group_code" => $branch_code.".".$group_code
            ));
            $log->activity = "berhasil menghapus data master group";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }
}