<?php



class Logout extends CI_Controller{

    public function __construct(){

        parent::__construct();

        $this->load->helper('form');

        $this->load->library('form_validation');

        $this->load->library('session');

    }



    public function index(){

        $this->session->sess_destroy();
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'];
        redirect($url);

    }

}



?>