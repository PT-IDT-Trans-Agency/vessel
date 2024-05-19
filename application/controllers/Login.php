<?php

class Login extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index(){
        $this->data_view["username"] = "";
        $this->data_view["password"] = "";
        $this->data_view["data_cabang"] = $this->db->get("branch")->result();
        $this->load->view("login",$this->data_view);
    }



    private function hashPassword($pwd){
        $result = "";
        $result = md5($pwd);
        // var_dump($result);exit();
        return $result;
    }





    public function post(){
        $this->form_validation->set_rules("username", "Username", "required|trim");
        $this->form_validation->set_rules("password", "Password", "required|trim");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $branch_code = $this->input->post("branch_code");

        if ($this->form_validation->run() == false) {
            $this->data_view["pesan"] = validation_errors();
            $this->data_view["username"] = $username;
            $this->data_view["password"] = $password;
            $this->data_view["data_cabang"] = $this->db->get("branch")->result();
            $this->load->view("login",$this->data_view);
        } else {
            $this->db->select("users.*, branch.branch_name, branch.holding");
            $this->db->from("users");
            $this->db->join("branch", "users.branch_code=branch.branch_code");
            $this->db->where(array("username"=>$username, "password"=>$this->hashPassword($password), "users.branch_code" => $branch_code));
            $data_user = $this->db->get()->result();
            if (count($data_user) < 1){
                $this->data_view["pesan"] = "Username atau Password anda salah";
                $this->data_view["username"] = $username;
                $this->data_view["password"] = $password;
                $this->data_view["data_cabang"] = $this->db->get("branch")->result();
                $this->load->view("login",$this->data_view);
            } else {
                foreach ($data_user as $user) {
                    $this->session->set_userdata("user_type", $user->type);
                    $this->session->set_userdata("user_name", $user->username);
                    $this->session->set_userdata("user_nama_cabang" , $user->branch_name);
                    $this->session->set_userdata("user_kode_cabang" , $user->branch_code);
                    $this->session->set_userdata("user_holding" , $user->holding);
                    foreach ($this->db->get("menu")->result() as $menu) {
                        foreach ($this->db->get_where("group_access", array("group_code" => $user->type, "menu_code" => $menu->menu_code))->result() as $group_access) {
                            $this->session->set_userdata($menu->menu_code, $group_access);
                        }
                    }
                }
                redirect(base_url("vessel-line-up"));
            }
        }
    }
}

?>
