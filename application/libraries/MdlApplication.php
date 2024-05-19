<?php
class MdlApplication {
   const JTRAN_WORKJOB = "WORKJOB";
   const JTRAN_EPDA = "EPDA";

   public function __construct(){
      $this->CI =& get_instance();
      $this->CI->load->database();
      $this->CI->load->helper("url");
      $this->CI->load->library("session");

   }
   function format_tanggal($tgl){
      return substr($tgl, 8, 2).'-'. substr($tgl, 5, 2).'-'.substr($tgl, 0, 4);
   }

   public function get_last_number_trx($jenis_transaksi){
      $ret = 0;
      switch ($jenis_transaksi) {
         case 'WORKJOB':
            $sys_auto_number = $this->CI->db->select("*")->from("sys_auto_number")->get()->result();
            foreach ($sys_auto_number as $wj) {
               $ret = $wj->workjob_no + 1;
            }
            break;
         case 'EPDA':
            $sys_auto_number = $this->CI->db->select("*")->from("sys_auto_number")->get()->result();
            foreach ($sys_auto_number as $epda) {
               $ret = $epda->epda_no + 1;
            }
            break;
         default:
            break;
      }

      return $ret;
   }

   function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
}
?>