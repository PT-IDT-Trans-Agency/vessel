<?php

class Auth {
    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->helper("url");
        $this->CI->load->library("session");
    }

    public function UserGroupAuth($group_code, $menu_code, $action){
        $this->CI->db->select("*");
        $this->CI->db->from("group_access");
        $this->CI->db->where(array(
            "group_code" => $group_code,
            "menu_code" => $menu_code,
            $action => 1
        ));
        if (count($this->CI->db->get()->result()) > 0){
            return true;
        } else {
            return false;
        }
    }
}
?>