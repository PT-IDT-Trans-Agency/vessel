<?php
    class Log {
        private $CI;
        private $identity;
        private $menu_code;
        public $activity;

        function __construct($identity, $menu_code){
            $this->CI =& get_instance();
            $this->CI->load->database();

            $this->identity = $identity;
            $this->menu_code = $menu_code;
        }

        public function record_log(){
            $this->CI->db->insert("user_log", array(
                "identity" => $this->identity,
                "menu_code" => $this->menu_code,
                "activity" => $this->activity,
            ));
        }
    }
?>