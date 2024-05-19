<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/Log.php");
class Apiprincipal extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
    }

    public function gets_master_principal(){
        $object["data"] = $this->db->get("principal")->result();
        echo json_encode($object);
    }

    public function get_information_principal($principal_code){
        $object["data"] = $this->db->get_where("principal", array("principal_code"=>$principal_code))->result();
        echo json_encode($object);
    }

    public function update_data_principal(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_MasterPrincipal");

        $principal_code = $this->input->post("principal_code");
        $principal_name = $this->input->post("principal_name");

        $data_principal["principal_name"] = $principal_name;
        $this->db->update("principal", $data_principal, array("principal_code"=>$principal_code));
        $object["success"] = 1;
        echo json_encode($object);
    }

    public function is_duplicate_principal($principal_code){
        $ret = false;
        if (count($this->db->get_where("principal", array("principal_code"=>$principal_code))->result()) > 0){
            $ret = true;
        }
        $object["is_duplicate"] = $ret;
        echo json_encode($object);
    }

    public function insert_principal(){
        $data_principal["principal_code"] = $this->input->post("principal_code");
        $data_principal["principal_name"] = $this->input->post("principal_name");
        $this->db->insert("principal",$data_principal);
        $object["success"] = 1;
        echo json_encode($object);
    }

}