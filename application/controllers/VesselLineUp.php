<?php
require_once(APPPATH."libraries/autoload.php");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once(APPPATH."libraries/Log.php");
class VesselLineUp extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('Globals');
        $this->load->library("Auth");
        if ($this->session->user_name == null || $this->session->user_name == ""){
            redirect(base_url());
        }
    }

    public function index(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "list")){
            $log->activity = "gagal membuka form list";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $log->activity = "membuka form list";
            $log->record_log();
            $this->data_view["data_branch"] = $this->db->get("branch")->result();
            $this->load->view("vessel-line-up/index", $this->data_view);
        }
    }

    public function add(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "create")){
            $log->activity = "gagal membuka form vessel line up";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $this->data_view["data_branch"] = $this->db->get("branch")->result();
            $this->data_view["line_up_no"] = $this->input->get("line_up_no");
            $this->load->view("vessel-line-up/form",$this->data_view);
        }
        
    }

    public function edit(){
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "update")){
            $log->activity = "gagal membuka form vessel line up";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $this->data_view["data_branch"] = $this->db->get("branch")->result();
            $this->data_view["line_up_no"] = $this->input->get("line_up_no");
            $this->load->view("vessel-line-up/form",$this->data_view);
        }
    }

    public function save(){
        redirect(base_url("vessel-line-up"));
    }

    public function export_excel(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $otorisasi = new Auth();
        $log = new Log($this->session->user_type.".".$this->session->user_name, "qry_VesselLineUp");
        if (!$otorisasi->UserGroupAuth($this->session->user_type, "qry_VesselLineUp", "print")){
            $log->activity = "gagal membuka print/export vessel line up";
            $log->record_log();
            $this->load->view("template/permission");
        } else {
            $dari_tanggal_eta = $this->input->get("dari_tanggal_eta");
            $sampai_tanggal_eta = $this->input->get("sampai_tanggal_eta");
            $cargo_type = $this->input->get("cargo_type");
            $arr_col_index = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S"];
            $arr_caption_col = ["Vessel Name", "Port Calling", "Activity", "Cargo Name", "Cargo Type", 
                                "Cargo Qty", "ETA", "ETB", "ETC", "ETD", "Destination", "Agent", "Shipper", "Buyer",
                                "Cabang", "Notify", "Remark", "Status Activity", "Owner/Principal"];
            $first_row_data = 5;
            if ($cargo_type == ""){
                $cargo_type = "Semua Tipe";
            }

            if ($this->input->get("branch_code") == ""){
                $branch_name = "Semua Cabang";
            } else {
                foreach ($this->db->get_where("branch", array("branch_code" => $this->input->get("branch_code")))->result() as $branch) {
                    $branch_name = $branch->branch_name;
                }
            }

            $data_exp_line_up_show_col = $this->db->get_where("export_line_up_show_column", array("username" => $this->session->user_name))->result();
            
            $sheet->getStyle("A1")->getFont()->setSize(16)->setBold(true);
            $sheet->setCellValue('A1', 'Vessel Line Up');
            $sheet->setCellValue('A2', 'Exported by :');
            $sheet->setCellValue('B2', $this->session->user_name);
            $sheet->setCellValue('A3', 'Exported at :');
            $sheet->setCellValue('B3', date("Y-m-d h:i:s"));
            $sheet->setCellValue('D2', 'Cabang :');
            $sheet->setCellValue('E2', $branch_name);
            $sheet->setCellValue('D3', 'Dari Tanggal s/d Tanggal :');
            $sheet->setCellValue('E3', $dari_tanggal_eta." s/d ".$sampai_tanggal_eta);
            $sheet->setCellValue('F2', 'Cargo Type :');
            $sheet->setCellValue('G2', $cargo_type);

            $col_index = 0;
            foreach ($data_exp_line_up_show_col as $col) {
                if ($col->vessel_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Vessel Name");
                    $col_index += 1;
                } 
                if ($col->port_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Port Calling");
                    $col_index += 1;
                } 
                if ($col->activity == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Actiity");
                    $col_index += 1;
                } 
                if ($col->cargo_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Cargo Name");
                    $col_index += 1;
                } 
                if ($col->cargo_type == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Cargo Type");
                    $col_index += 1;
                } 
                if ($col->cargo_qty == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Cargo Qty");
                    $col_index += 1;
                } 
                if ($col->eta == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "ETA");
                    $col_index += 1;
                } 
                if ($col->etb == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "ETB");
                    $col_index += 1;
                } 
                if ($col->etc == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "ETC");
                    $col_index += 1;
                } 
                if ($col->etd == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "ETD");
                    $col_index += 1;
                } 
                if ($col->destination == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Destination");
                    $col_index += 1;
                } 
                if ($col->agent_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Agent");
                    $col_index += 1;
                } 
                if ($col->shipper_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Shipper");
                    $col_index += 1;
                } 
                if ($col->buyer_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Buyer");
                    $col_index += 1;
                } 
                if ($col->branch_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Cabang");
                    $col_index += 1;
                } 
                if ($col->notify == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Notify");
                    $col_index += 1;
                } 
                if ($col->remark == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Remark");
                    $col_index += 1;
                } 
                if ($col->status_activity == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Status Activity");
                    $col_index += 1;
                } 
                if ($col->principal_name == 1){
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getFont()->setBold(true);
                    $sheet->getStyle($arr_col_index[$col_index]."5")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $sheet->setCellValue($arr_col_index[$col_index]."5", "Onwer/Principal");
                    $col_index += 1;
                }
            }
    
            $branch_code = $this->input->get("branch_code");
    
            if ($branch_code != ""){
                $data_where["line_up.branch_code"]= $branch_code;
            }
    
            $this->db->select("line_up.*,branch.branch_name, left(date_format(line_up.created_at, '%M'),3) as month");
            $this->db->from("line_up");
            $this->db->join("branch", "line_up.branch_code=branch.branch_code");
            if (isset($data_where)){
                $this->db->where($data_where);
            }
            $this->db->where("date_format(line_up.eta, '%Y-%m-%d') >= '".$dari_tanggal_eta."' AND date_format(line_up.eta, '%Y-%m-%d') <= '".$sampai_tanggal_eta."'");
            $no_row = 6;
            foreach ($this->db->get()->result() as $line_up) {
                $col_index = 0;
                foreach ($data_exp_line_up_show_col as $col) {
                    if ($col->vessel_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);    
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->vessel_name);
                        $col_index += 1;
                    } 
                    if ($col->port_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->port_name);
                        $col_index += 1;
                    } 
                    if ($col->activity == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->activity);
                        $col_index += 1;
                    } 
                    if ($col->cargo_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->cargo_name);
                        $col_index += 1;
                    } 
                    if ($col->cargo_type == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->cargo_type);
                        $col_index += 1;
                    } 
                    if ($col->cargo_qty == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->cargo_qty);
                        $col_index += 1;
                    } 
                    if ($col->eta == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->eta);
                        $col_index += 1;
                    } 
                    if ($col->etb == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->etb);
                        $col_index += 1;
                    } 
                    if ($col->etc == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->etc);
                        $col_index += 1;
                    } 
                    if ($col->etd == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->etd);
                        $col_index += 1;
                    } 
                    if ($col->destination == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->destination_name);
                        $col_index += 1;
                    } 
                    if ($col->agent_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->agent_name);
                        $col_index += 1;
                    } 
                    if ($col->shipper_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->shipper_name);
                        $col_index += 1;
                    } 
                    if ($col->buyer_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->buyer_name);
                        $col_index += 1;
                    } 
                    if ($col->branch_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->branch_name);
                        $col_index += 1;
                    } 
                    if ($col->notify == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->notify);
                        $col_index += 1;
                    } 
                    if ($col->remark == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->remark);
                        $col_index += 1;
                    } 
                    if ($col->status_activity == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->status_activity);
                        $col_index += 1;
                    } 
                    if ($col->principal_name == 1){
                        $sheet->getStyle($arr_col_index[$col_index].$no_row)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $sheet->setCellValue($arr_col_index[$col_index].$no_row, $line_up->owner);
                        $col_index += 1;
                    }
                }
                $no_row++;
            }
    
            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save('Export Lineup.xlsx');
                $content = file_get_contents('Export Lineup.xlsx');
            } catch(Exception $e) {
                exit($e->getMessage());
            }
    
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            exit($content);
        }
    }

    

    public function print_wj($id){
        $this->load->library('fpdf');
        // $this->load->library('writehtml');
        $gf = new Globals();

        $this->db->select("*");
        $this->db->from("workjob");
        $this->db->join("shipper", "workjob.shipper_code=shipper.shipper_code");
        $this->db->join("port", "workjob.port_code=port.port_code");
        $this->db->join("vessel", "workjob.vessel_code=vessel.vessel_code");
        $this->db->join("principal", "workjob.principal_code=principal.principal_code");
        $this->db->where("workjob.id", $id);
        foreach ($this->db->get()->result() as $workjob) {
            $data_workjob["workjob_no"] = $workjob->workjob_no;
            $data_workjob["principal_name"] = $workjob->principal_name;
            $data_workjob["port_name"] = $workjob->port_name;
            $data_workjob["activity"] = $workjob->activity;
            $data_workjob["cargo_name"] = $workjob->cargo_name;
            $data_workjob["cargo_qty"] = $workjob->cargo_qty;
            $data_workjob["ta"] = $workjob->ta;
            $data_workjob["vessel_name"] = $workjob->vessel_name;
            $data_workjob["dwt"] = $workjob->dwt;
            $data_workjob["gt"] = $workjob->gt;
            $data_workjob["td"] = $workjob->td;
            $data_workjob["nilai_tukar"] = $workjob->nilai_tukar;
            $data_workjob["ta"] = $workjob->ta;
            $data_workjob["currency_code"] = $workjob->currency_code;
            $data_workjob["workjob_no"] = $workjob->workjob_no;
            $data_workjob["sum_service_value"] = $workjob->sum_service_value;
        }

        foreach ($this->db->get_where("bank", array("currency_code"=>$data_workjob["currency_code"]))->result() as $bank) {
            $data_bank["bank_name"]  = $bank->bank_name;
            $data_bank["bank_address"]  = $bank->bank_address;
            $data_bank["account_no"]  = $bank->account_no;
            $data_bank["swift_code"]  = $bank->swift_code;
            $data_bank["ifo"]  = $bank->ifo;
        }

        $pdf = new FPDF('P','mm','A4');
        $pdf->SetMargins(10,15,25);
        // $html2pdf = new PDF_HTML();

        $pdf->AddPage();
        $pdf->SetFont('Times','',11.5);
        $title = 'Advance Request';
        
        $pdf->SetTitle($title);
        $pdf->SetAuthor($this->session->username);
        $pdf->SetFont('Arial','B',13.5);
		$pdf->Image(base_url("docs/img/logo.png"),5,5,30,15,"PNG");
        $pdf->SetY(10);
        $pdf->SetX(80);
        $pdf->Cell(40, 5, 'Proforma Disbursement Accounts',0,1,'C');
        $pdf->SetY(16);
        $pdf->SetX(80);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(40, 5, $data_workjob["workjob_no"],0,1,'C');
        $pdf->SetY(15);
        $pdf->SetX(60);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'Principal ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper($data_workjob["principal_name"]),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'Date ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, date("d/m/Y"),0,1,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'Port ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper($data_workjob["port_name"]),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'Vessel ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, $data_workjob["vessel_name"],0,1,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'Purpose of Call ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper($data_workjob["activity"]),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'DWT ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, number_format($data_workjob["dwt"],0,".",","),0,1,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'Cargo ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper($data_workjob["cargo_name"]),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'GT ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, number_format($data_workjob["gt"],0,".",","),0,1,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'Quantity ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper(number_format($data_workjob["cargo_qty"],0,".",",")." MT +/- 10% MOLOO"),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'Currency ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, $data_workjob["currency_code"],0,1,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30, 5, 'ETA ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(85, 5, strtoupper($data_workjob["ta"]),0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(25, 5, 'ETD ',0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(80, 5, strtoupper($data_workjob["td"]),0,1,'L');

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFillColor(51,104,198);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(110, 7, "ITEM/DESCRIPTIONS",1,0,'C', true);
        $pdf->Cell(30, 7, "Amount",1,0,'C', true);
        $pdf->Cell(50, 7, "Remarks",1,1,'C', true);
        $pdf->SetTextColor(0,0,0);

        $this->db->select("service_category.category_name");
        $this->db->from("workjob_detail");
        $this->db->join("service_category", "workjob_detail.category_code=service_category.category_code");
        $this->db->where("workjob_detail.workjob_no",$data_workjob["workjob_no"]);
        $this->db->group_by("service_category.category_name");
        $this->db->order_by("service_category.category_name", "desc");
        foreach ($this->db->get()->result() as $wd) {
            $pdf->SetFont('Arial','B',9);
            $pdf->SetFillColor(51,104,198);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(190, 7, $wd->category_name,1,1,'L', true);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',9);
            $this->db->select("*");
            $this->db->from("workjob_detail");
            $this->db->join("service_category", "workjob_detail.category_code=service_category.category_code");
            $this->db->join("service", "workjob_detail.service_code=service.service_code");
            
            $this->db->where("workjob_detail.workjob_no",$data_workjob["workjob_no"]);
            $data_workjob_detail = $this->db->get()->result();
            $incre_count_row = 0;
            foreach ($data_workjob_detail as $workjob_detail) {
                $incre_count_row += 1;
                if ($wd->category_name == $workjob_detail->category_name){
                    if (count($data_workjob_detail) == $incre_count_row){
                        $pdf->Cell(110, 7, $workjob_detail->service_name,'L,R',0,'L');
                        $pdf->Cell(30, 7, number_format($workjob_detail->service_value,2,".",","),'L,R,B',0,'R');
                        $pdf->Cell(50, 7, $workjob_detail->remarks,'L,R,B',1,'C');
                    } else {
                        $pdf->Cell(110, 7, $workjob_detail->service_name,'L,R',0,'L');
                        $pdf->Cell(30, 7, number_format($workjob_detail->service_value,2,".",","),'L,R',0,'R');
                        $pdf->Cell(50, 7, $workjob_detail->remarks,'L,R',1,'C');
                    }
                }
            }
        }
        
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(110, 7, "GRAND TOTAL DISBURSEMENT",1,0,'C');
        $pdf->Cell(30, 7, number_format($data_workjob["sum_service_value"],2,".",","),1,0,'R');
        $pdf->Cell(50, 7, "",1,1,'L');
        $pdf->Ln();
        $pdf->MultiCell(190, 5, "Our bank details are enclosed herebelow and we would appreciate your remittance together with a banking advice for our monitoring and tracing of funds",0,'L');
        $pdf->SetTextColor(255,0,0);
        $pdf->MultiCell(190, 5, "We. PT IDT TRANS AGENCY does not have plane to move address, change email domains, change Bank Account numbers or whatever. Should you have any receive communication the authenticity of which is doubtful, do not hesitate to contact this office or undersigned at any time.",0,'L');
        $pdf->SetTextColor(0,0,0);
        $pdf->MultiCell(190, 5, "In case of urgent messages please follow up with a phone call in order to make sure that message will be attended to immadiately.",0,'L');
        $pdf->Cell(10, 5, "",0,0,'L');
        $pdf->Cell(30, 5, "Bank Name ",0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50, 5, $data_bank["bank_name"],0,1,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10, 5, "",0,0,'L');
        $pdf->Cell(30, 5, "Bank Address ",0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50, 5, $data_bank["bank_address"],0,1,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10, 5, "",0,0,'L');
        $pdf->Cell(30, 5, "Account No ",0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50, 5, $data_bank["account_no"],0,1,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10, 5, "",0,0,'L');
        $pdf->Cell(30, 5, "Swift Code ",0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50, 5, $data_bank["swift_code"],0,1,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(10, 5, "",0,0,'L');
        $pdf->Cell(30, 5, "IFO ",0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50, 5, $data_bank["ifo"],0,1,'L');

        $pdf->Output("I");
    }

}