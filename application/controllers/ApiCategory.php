<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class ApiCategory extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
    }

    public function gets_master_category(){
        $object["data"] = $this->db->get("service_category")->result();
        echo json_encode($object);
    }

    public function get_information_category($category_code){
        $object["data"] = $this->db->get_where("service_category", array("category_code"=>$category_code))->result();
        echo json_encode($object);
    }

    public function update_data_category(){
        $category_code = $this->input->post("category_code");
        $category_name = $this->input->post("category_name");

        $data_category["category_name"] = $category_name;
        $this->db->update("service_category", $data_category, array("category_code"=>$category_code));
        $object["success"] = 1;
        echo json_encode($object);
    }

    public function is_duplicate_category($category_code){
        $ret = false;
        if (count($this->db->get_where("service_category", array("category_code"=>$category_code))->result()) > 0){
            $ret = true;
        }
        $object["is_duplicate"] = $ret;
        echo json_encode($object);
    }

    public function insert_category(){
        $data_category["category_code"] = $this->input->post("category_code");
        $data_category["category_name"] = $this->input->post("category_name");
        $this->db->insert("service_category",$data_category);
        $object["success"] = 1;
        echo json_encode($object);
    }

}