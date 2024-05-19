<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Api extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    

    public function is_duplicate_kode($kode){
        $ret = false;
        $this->db->select("*");
        $this->db->from("kegiatan");
        $this->db->where("kode" ,$kode);
        if (count($this->db->get()->result()) > 0) {
            $ret = true;
        }
        $object["is_duplicate"] = $ret;
        echo json_encode($object);
    }

    public function is_duplicate_username($username){
        $ret = false;
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("username" ,$username);
        if (count($this->db->get()->result()) > 0) {
            $ret = true;
        }

        $object["is_duplicate"] = $ret;
        echo json_encode($object);
    }



    public function insert_kegiatan(){

        $kode = $this->input->post("kode");

        $nama_satuan_kerja = $this->input->post("nama_satuan_kerja");

        $nama_kegiatan = $this->input->post("nama_kegiatan");

        $tahun_kontrak = $this->input->post("tahun_kontrak");

        $deskripsi = $this->input->post("deskripsi");

        $keterangan = $this->input->post("keterangan");

        $tahun_berjalan = $this->input->post("tahun_berjalan");

        $bulan_progres_fisik = $this->input->post("bulan_progres_fisik");

        $rencana = $this->input->post("rencana");

        $realisasi = $this->input->post("realisasi");

        $deviasi = $this->input->post("deviasi");



        $data_kegiatan["kode"] = $kode;

        $data_kegiatan["nama_satuan_kerja"] = $nama_satuan_kerja;

        $data_kegiatan["nama_kegiatan"] = $nama_kegiatan;

        $data_kegiatan["tahun_kontrak"] = $tahun_kontrak;

        $data_kegiatan["deskripsi"] = $deskripsi;

        $data_kegiatan["keterangan"] = $keterangan;

        $data_kegiatan["tahun_berjalan"] = $tahun_berjalan;

        $data_kegiatan["bulan_progres_fisik"] = $bulan_progres_fisik;

        $data_kegiatan["rencana"] = $rencana;

        $data_kegiatan["realisasi"] = $realisasi;

        $data_kegiatan["deviasi"] = $deviasi;

        $data_kegiatan["username"] = $this->session->user_name;



        $this->db->insert("kegiatan", $data_kegiatan);

        $object["success"] = "1";

        $object["data"] = $data_kegiatan;

        echo json_encode($object);

    }



    public function update_kegiatan(){

        $id = $this->input->post("id");

        $kode = $this->input->post("kode");

        $nama_satuan_kerja = $this->input->post("nama_satuan_kerja");

        $nama_kegiatan = $this->input->post("nama_kegiatan");

        $tahun_kontrak = $this->input->post("tahun_kontrak");

        $deskripsi = $this->input->post("deskripsi");

        $keterangan = $this->input->post("keterangan");

        $tahun_berjalan = $this->input->post("tahun_berjalan");

        $bulan_progres_fisik = $this->input->post("bulan_progres_fisik");

        $rencana = $this->input->post("rencana");

        $realisasi = $this->input->post("realisasi");

        $deviasi = $this->input->post("deviasi");



        $data_kegiatan["kode"] = $kode;

        $data_kegiatan["nama_satuan_kerja"] = $nama_satuan_kerja;

        $data_kegiatan["nama_kegiatan"] = $nama_kegiatan;

        $data_kegiatan["tahun_kontrak"] = $tahun_kontrak;

        $data_kegiatan["deskripsi"] = $deskripsi;

        $data_kegiatan["keterangan"] = $keterangan;

        $data_kegiatan["tahun_berjalan"] = $tahun_berjalan;

        $data_kegiatan["bulan_progres_fisik"] = $bulan_progres_fisik;

        $data_kegiatan["rencana"] = $rencana;

        $data_kegiatan["realisasi"] = $realisasi;

        $data_kegiatan["deviasi"] = $deviasi;

        $data_kegiatan["username"] = $this->session->user_name;



        $this->db->update("kegiatan", $data_kegiatan, array("id"=>$id));



        

        $object["success"] = "1";

        $object["data"] = $data_kegiatan;

        echo json_encode($object);

    }



    public function gets_kegiatan_dashboard(){

        $tahun_berjalan = $this->input->post("tahun_berjalan");

        $bulan_progres_fisik = $this->input->post("bulan_progres_fisik");

        $username = $this->session->user_name;



        $this->db->select("*");

        $this->db->from("kegiatan");

        if ($this->session->user_type == "ADMIN"){

            $this->db->where(array("tahun_berjalan"=>$tahun_berjalan, "bulan_progres_fisik"=>$bulan_progres_fisik));

        } else {

            $this->db->where(array("tahun_berjalan"=>$tahun_berjalan, "bulan_progres_fisik"=>$bulan_progres_fisik, "username"=>$username));

        }

        

        $data_kegiatan = $this->db->get()->result();

        $object["data"] = $data_kegiatan;

        echo json_encode($object);



    }



    public function get_information_kegiatan($id){

        $this->db->select("*");

        $this->db->from("kegiatan");

        $this->db->where("id", $id);

        $object["data"] = $this->db->get()->result();

        echo json_encode($object);

    }



    public function get_autocomplete_kegiatan(){

        $nama_satuan_kerja = $this->input->post("nama_satuan_kerja");

        $this->db->select("nama_satuan_kerja");

        $this->db->from("kegiatan");

        $this->db->like("nama_satuan_kerja",$nama_satuan_kerja);

        $this->db->group_by("nama_satuan_kerja");

        $object["data"] = $this->db->get()->result();

        echo json_encode($object);

    }



}