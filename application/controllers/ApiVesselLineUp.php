<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/autoload.php");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once(APPPATH."libraries/Log.php");
class ApiVesselLineUp extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('MdlApplication');
        $this->load->library('Globals');
        $this->load->library("Auth");
        if ($this->session->user_name == null) {
            redirect(base_url());
        }
    }

    public function gets_line_up(){
        $branch_code = $this->input->post("branch_code");
        $dari_tanggal_eta = $this->input->post("dari_tanggal_eta");
        $sampai_tanggal_eta = $this->input->post("sampai_tanggal_eta");
        $port_code = $this->input->post("port_code");
        $cargo_type = $this->input->post("cargo_type");

        if ($branch_code != ""){
            $data_where["line_up.branch_code"]= $branch_code;
        }

        if ($port_code != ""){
            $data_where["line_up.port_code"]= $port_code;
        }

        if ($cargo_type != ""){
            $data_where["line_up.cargo_type"]= $cargo_type;
        }

        $this->db->select("line_up.*, left(date_format(line_up.created_at, '%M'),3) as month");
        $this->db->from("line_up");
        if (isset($data_where)){
            $this->db->where($data_where);
            
        }
        $this->db->where("date_format(line_up.eta, '%Y-%m-%d') >= '".$dari_tanggal_eta."' AND date_format(line_up.eta, '%Y-%m-%d') <= '".$sampai_tanggal_eta."'");

        $object["data"] = $this->db->get()->result();
        $object["date_last_updated"] =  "-";
        $object["time_last_updated"] =  "-";
        $date_last_updated = null;
        $date_last_created = null;

        $this->db->select("*");
        $this->db->from("line_up");
        if ($branch_code != ""){
            $this->db->where("branch_code", $branch_code);
        }
        $this->db->order_by("created_at", "desc");
        $this->db->limit(1);
        foreach ($this->db->get()->result() as $line_up) {
            $date_last_created = $line_up->created_at;
        }

        $this->db->select("*");
        $this->db->from("line_up");
        if ($branch_code != ""){
            $this->db->where("branch_code", $branch_code);
        }
        $this->db->order_by("updated_at", "desc");
        $this->db->limit(1);
        foreach ($this->db->get()->result() as $line_up) {
            $date_last_updated = $line_up->updated_at;
        }

        if ($date_last_created > $date_last_updated){
            $date_last_updated = $date_last_created;
        }

        $object["date_last_updated"] =  date_format(date_create($date_last_updated), "d M Y");
        $object["time_last_updated"] =  date_format(date_create($date_last_updated), "H.i");

        echo json_encode($object);
    }

    public function get_line_up(){
        $line_up_no = $this->input->get("line_up_no");
        $object = [];
        $object["exists"] = 0;
        foreach ($this->db->get_where("line_up", array("line_up_no" => $line_up_no))->result() as $line_up) {
            $object["exists"] = 1;
            $object["branch_code"] = $line_up->branch_code;
            $object["eta"] = $line_up->eta; 
            $object["etb"] = $line_up->etb; 
            $object["etc"] = $line_up->etc; 
            $object["etd"] = $line_up->etd; 
            $object["shipper_code"] = $line_up->shipper_code;
            $object["shipper_name"] = $line_up->shipper_name;
            $object["vessel_code"] = $line_up->vessel_code;
            $object["vessel_name"] = $line_up->vessel_name;
            $object["port_code"] = $line_up->port_code;
            $object["port_name"] = $line_up->port_name;
            $object["owner"] = $line_up->owner;
            $object["cargo_name"] = $line_up->cargo_name;
            $object["cargo_type"] = $line_up->cargo_type;
            $object["cargo_qty"] = $line_up->cargo_qty;
            $object["destination_code"] = $line_up->destination_code;
            $object["destination_name"] = $line_up->destination_name;
            $object["agent_code"] = $line_up->agent_code;
            $object["agent_name"] = $line_up->agent_name;
            $object["pbm_code"] = $line_up->pbm_code;
            $object["pbm_name"] = $line_up->pbm_name;
            $object["buyer_code"] = $line_up->buyer_code;
            $object["buyer_name"] = $line_up->buyer_name;
            $object["activity"] = $line_up->activity;
            $object["notify"] = $line_up->notify;
            $object["remark"] = $line_up->remark;
            $object["status_activity"] = $line_up->status_activity;
        }

        echo json_encode($object);
    }

    public function get_export_set_column_show(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        $object = [];
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "create")){
            $log->activity = "gagal menyimpan data vessel line up";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $username = $this->session->user_name;
            $data_setting = $this->db->get_where("export_line_up_show_column", array("username" => $username))->result();
            foreach ($data_setting as $item) {
                $object["vessel_name"] = $item->vessel_name;
                $object["port_name"] = $item->port_name;
                $object["activity"] = $item->activity;
                $object["cargo_name"] = $item->cargo_name;
                $object["cargo_type"] = $item->cargo_type;
                $object["cargo_qty"] = $item->cargo_qty;
                $object["eta"] = $item->eta;
                $object["etb"] = $item->etb;
                $object["etc"] = $item->etc;
                $object["etd"] = $item->etd;
                $object["destination"] = $item->destination;
                $object["agent_name"] = $item->agent_name;
                $object["shipper_name"] = $item->shipper_name;
                $object["buyer_name"] = $item->buyer_name;
                $object["branch_name"] = $item->branch_name;
                $object["notify"] = $item->notify;
                $object["remark"] = $item->remark;
                $object["status_activity"] = $item->status_activity;
                $object["principal_name"] = $item->principal_name;
            }
        }
        echo json_encode($object);
    }

    public function update_export_set_column_show(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        $object = [];
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "create")){
            $log->activity = "gagal menyimpan data vessel line up";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $username = $this->session->user_name;
            $data["vessel_name"] = $this->input->post("vessel_name");
            $data["port_name"] = $this->input->post("port_name");
            $data["activity"] = $this->input->post("activity");
            $data["cargo_name"] = $this->input->post("cargo_name");
            $data["cargo_type"] = $this->input->post("cargo_type");
            $data["cargo_qty"] = $this->input->post("cargo_qty");
            $data["eta"] = $this->input->post("eta");
            $data["etb"] = $this->input->post("etb");
            $data["etc"] = $this->input->post("etc");
            $data["etd"] = $this->input->post("etd");
            $data["destination"] = $this->input->post("destination");
            $data["agent_name"] = $this->input->post("agent_name");
            $data["shipper_name"] = $this->input->post("shipper_name");
            $data["buyer_name"] = $this->input->post("buyer_name");
            $data["branch_name"] = $this->input->post("branch_name");
            $data["notify"] = $this->input->post("notify");
            $data["remark"] = $this->input->post("remark");
            $data["status_activity"] = $this->input->post("status_activity");
            $data["principal_name"] = $this->input->post("principal_name");
            $this->db->update("export_line_up_show_column", $data, array("username" => $username));
            $object["success"] = 1;
            $object["message"] = "Data berhasil disimpan";
        }
        echo json_encode($object);
    }

    public function update_data_line_up(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        $line_up_no = $this->input->post("line_up_no");
        $eta = $this->input->post("eta");
        $etb = $this->input->post("etb");
        $etc = $this->input->post("etc");
        $etd = $this->input->post("etd");
        $branch_code = $this->input->post("branch_code");
        $shipper_code = $this->input->post("shipper_code");
        $shipper_name = $this->input->post("shipper_name");
        $vessel_code = $this->input->post("vessel_code");
        $vessel_name = $this->input->post("vessel_name");
        $port_code = $this->input->post("port_code");
        $port_name = $this->input->post("port_name");
        $owner = $this->input->post("owner");
        $cargo_name = $this->input->post("cargo_name");
        $cargo_type = $this->input->post("cargo_type");
        $cargo_qty = $this->input->post("cargo_qty");
        $destination_code = $this->input->post("destination_code");
        $destination_name = $this->input->post("destination_name");
        $agent_code = $this->input->post("agent_code");
        $agent_name = $this->input->post("agent_name");
        $pbm_code = $this->input->post("pbm_code");
        $pbm_name = $this->input->post("pbm_name");
        $buyer_code = $this->input->post("buyer_code");
        $buyer_name = $this->input->post("buyer_name");
        $activity = $this->input->post("activity");
        $notify = $this->input->post("notify");
        $remark = $this->input->post("remark");
        $status_activity = $this->input->post("status_activity");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "create")){
            $log->activity = "gagal menyimpan data vessel line up";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            if (count($this->db->get_where("line_up", array("line_up_no"=>$line_up_no))->result()) > 0){
                if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "update")){
                    $log->activity = "gagal mengubah data vessel line up";
                    $log->record_log();
                    $object["success"] = 0;
                    $object["message"] = "you dont have permission, please contact your administrator";
                } else {
                    $data_line_up["branch_code"] = $branch_code;
                    $data_line_up["eta"] = $eta; 
                    $data_line_up["etb"] = $etb; 
                    $data_line_up["etc"] = $etc; 
                    $data_line_up["etd"] = $etd; 
                    $data_line_up["shipper_code"] = $shipper_code;
                    $data_line_up["shipper_name"] = $shipper_name;
                    $data_line_up["vessel_code"] = $vessel_code;
                    $data_line_up["vessel_name"] = $vessel_name;
                    $data_line_up["port_code"] = $port_code;
                    $data_line_up["port_name"] = $port_name;
                    $data_line_up["owner"] = $owner;
                    $data_line_up["cargo_name"] = $cargo_name;
                    $data_line_up["cargo_type"] = $cargo_type;
                    $data_line_up["cargo_qty"] = $cargo_qty;
                    $data_line_up["destination_code"] = $destination_code;
                    $data_line_up["destination_name"] = $destination_name;
                    $data_line_up["agent_code"] = $agent_code;
                    $data_line_up["agent_name"] = $agent_name;
                    $data_line_up["pbm_code"] = $pbm_code;
                    $data_line_up["pbm_name"] = $pbm_name;
                    $data_line_up["buyer_code"] = $buyer_code;
                    $data_line_up["buyer_name"] = $buyer_name;
                    $data_line_up["activity"] = $activity;
                    $data_line_up["notify"] = $notify;
                    $data_line_up["remark"] = $remark;
                    $data_line_up["status_activity"] = $status_activity;
                    $data_line_up["updated_at"] = date("Y-m-d H:i:s");
                    $this->db->update("line_up", $data_line_up, array("line_up_no" => $line_up_no));
                    $log->activity = "berhasil mengubah data vessel line up";
                    $object["success"] = 1;
                    $object["message"] = "Saving data is successfully";
                }
            } else {
                $data_line_up["branch_code"] = $branch_code;
                $data_line_up["eta"] = $eta; 
                $data_line_up["etb"] = $etb; 
                $data_line_up["etc"] = $etc; 
                $data_line_up["etd"] = $etd; 
                $data_line_up["shipper_code"] = $shipper_code;
                $data_line_up["shipper_name"] = $shipper_name;
                $data_line_up["vessel_code"] = $vessel_code;
                $data_line_up["vessel_name"] = $vessel_name;
                $data_line_up["port_code"] = $port_code;
                $data_line_up["port_name"] = $port_name;
                $data_line_up["owner"] = $owner;
                $data_line_up["cargo_name"] = $cargo_name;
                $data_line_up["cargo_type"] = $cargo_type;
                $data_line_up["cargo_qty"] = $cargo_qty;
                $data_line_up["destination_code"] = $destination_code;
                $data_line_up["destination_name"] = $destination_name;
                $data_line_up["agent_code"] = $agent_code;
                $data_line_up["agent_name"] = $agent_name;
                $data_line_up["pbm_code"] = $pbm_code;
                $data_line_up["pbm_name"] = $pbm_name;
                $data_line_up["buyer_code"] = $buyer_code;
                $data_line_up["buyer_name"] = $buyer_name;
                $data_line_up["activity"] = $activity;
                $data_line_up["notify"] = $notify;
                $data_line_up["remark"] = $remark;
                $data_line_up["status_activity"] = $status_activity;
                $data_line_up["created_by"] = $this->session->user_name;
                $data_line_up["created_at"] = date("Y-m-d H:i:s");
                $this->db->insert("line_up", $data_line_up);
                $log->activity = "berhasil menyimpan data vessel line up";
                $object["success"] = 1;
                $object["message"] = "Saving data is successfully";
            }
        }
        echo json_encode($object);        
    }

    public function delete(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        $line_up_no = $this->input->post("line_up_no");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "delete")){
            $log->activity = "gagal menghapus data vessel line up";
            $log->record_log();
            $object["success"] = 0;
            $object["message"] = "you dont have permission, please contact your administrator";
        } else {
            $this->db->delete("line_up", array("line_up_no"=>$line_up_no));
            $log->activity = "berhasil menghapus data vessel line up";
            $object["success"] = 1;
            $object["message"] = "Deleting data is successfully";
        }
        echo json_encode($object);
    }

    public function export_excel(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        // $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx');

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save('hello world.xlsx');
            $content = file_get_contents('hello world.xlsx');
        } catch(Exception $e) {
            exit($e->getMessage());
        }

        header("Content-Disposition: attachment; filename=".'hello world.xlsx');
        // unlink('hello world.xlsx');
        // exit($content);
    }

}