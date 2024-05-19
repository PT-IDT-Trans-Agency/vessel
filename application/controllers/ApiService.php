<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class ApiService extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
    }

    public function gets_master_service(){
        $category_code = $this->input->post("category_code");

        if ($category_code != ""){
            $data_where["service.category_code"] = $category_code;
        }

        $this->db->select("*");
        $this->db->from("service");
        $this->db->join("service_category", "service.category_code=service_category.category_code");
        if (isset($data_where)){
            $this->db->where($data_where);
        }
        $object["data"] = $this->db->get()->result();
        echo json_encode($object);
    }

    public function get_combo_service_by_category(){
        $service = null;
        $category_code = $this->input->get("category_code");
        if (!($category_code == "" || $category_code == null)){
            $data_service = $this->db->get_where("service", array("category_code"=>$category_code))->result();
            foreach ($data_service as $key => $row) {
                $service[$row->service_code] = $row->service_name;
            }
        }
        
        echo json_encode($service);
    }

    public function get_information_service($service_code){
        $object["data"] = $this->db->get_where("service", array("service_code"=>$service_code))->result();
        echo json_encode($object);
    }

    public function update_data_service(){
        $service_code = $this->input->post("service_code");
        $service_name = $this->input->post("service_name");

        $data_service["service_name"] = $service_name;
        $this->db->update("service", $data_service, array("service_code"=>$service_code));
        $object["success"] = 1;
        echo json_encode($object);
    }

    public function is_duplicate_service($service_code){
        $ret = false;
        if (count($this->db->get_where("service", array("service_code"=>$service_code))->result()) > 0){
            $ret = true;
        }
        $object["is_duplicate"] = $ret;
        echo json_encode($object);
    }

    public function insert_service(){
        $category_code = $this->input->post("category_code");
        $data_category = $this->db->get_where("service_category", array("category_code"=>$category_code))->result();
        foreach ($data_category as $category) {
            $last_number_child = $category->last_number_child;
        }
        $data_service["category_code"] = $category_code;
        $data_service["service_code"] = sprintf($category_code."%u", $last_number_child+1);
        $data_service["service_name"] = $this->input->post("service_name");
        $this->db->insert("service",$data_service);

        unset($data_category);
        $data_category["last_number_child"] = $last_number_child+1;
        $this->db->update("service_category", $data_category, array("category_code"=>$category_code));
        $object["success"] = 1;
        echo json_encode($object);
    }

}