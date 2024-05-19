<!doctype html>
<html lang="en">
  <head>    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url("css/style.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/datatables.net-bs4/css/dataTables.bootstrap4.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/toastr/css/toastr.min.css") ?>" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="<?= base_url("vendors/datatables.net/js/jquery.dataTables.js") ?>"></script>
    <script src="<?= base_url("vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js") ?>"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Vessel Line Up</title>
    <script type="text/javascript">
        var datas = null;
        var datas_item = {
            data : [],
        };

        $(document).ready(function(){
            $('#cargo_qty_modal').mask('###,###,###,###,###', {reverse: true});
            $('#gt_modal').mask('###,###,###,###,###', {reverse: true});
            $('#dwt_modal').mask('###,###,###,###,###', {reverse: true});
            $('#nilai_tukar_modal').mask('###,###,###,###,###.00', {reverse: true});
            $('#sum_service_value_modal').mask('###,###,###,###,###.00', {reverse: true});
            $('#service_value_modal').mask('###,###,###,###,###.00', {reverse: true});
            var columnsDefListVesselPrompt = ["Vessel Code","Vessel Name"];
            var columnsDefListPortPrompt = ["Port Code","Port Name"];
            var columnsDefListShipperPrompt = ["Shipper Code","Shipper Name"];
            var columnsDefListPrincipalPrompt = ["Principal Code","Principal Name"];
            var titleTextPrompt = ["Vessel List", "Port List", "Shipper List", "Principal List"];
            var tableNamePrompt = ["vessel", "port", "shipper", "principal"];
            SetDataViewAdvanceRequest();
            
            function ShowNotificationToaster(type, message, title) {
                Command: toastr[type](message, title)
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "100",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "swing",
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp"
                }
            }

            $("#branch_code").change(function(){
                SetDataViewAdvanceRequest();
            })

            $("#dari_tanggal_eta").change(function(){
                SetDataViewAdvanceRequest();
            })

            $("#sampai_tanggal_eta").change(function(){
                SetDataViewAdvanceRequest();
            })

            $("#port_code").change(function(){
                SetDataViewAdvanceRequest();
            })

            $("input[name=radioCoal]").change(function(){
                SetDataViewAdvanceRequest();
            })

            $("#btnExportExcel").click(function(){
                url = "<?= site_url("vessellineup/export_excel?branch_code=") ?>"+$("#branch_code").val()+"&dari_tanggal_eta="+$("#dari_tanggal_eta").val()+"&sampai_tanggal_eta="+$("#sampai_tanggal_eta").val()+"&cargo_type="+$("input[name=radioCoal]:checked").val();
                window.open(url)
            })

            $("#btnBersihkanFilterPort").click(function(){
                $("#port_name").val("")
                $("#port_code").val("")
                SetDataViewAdvanceRequest();
            })

             //event jquery
             $("#btnPromptPort").click(function(){
                SetDataViewPortList();
                $("#modalPortList").modal("show")
            })

            $("#btnSimpanExportSettingColShow").click(function(){
                data = {
                    vessel_name : ($("#ckVessel").is(":checked") ? 1 : 0),
                    port_name : ($("#ckPort").is(":checked") ? 1 : 0),
                    activity : ($("#ckActivity").is(":checked") ? 1 : 0),
                    cargo_name : ($("#ckCargo").is(":checked") ? 1 : 0),
                    cargo_type : ($("#ckCargoType").is(":checked") ? 1 : 0),
                    cargo_qty : ($("#ckCargoQty").is(":checked") ? 1 : 0),
                    eta : ($("#ckETA").is(":checked") ? 1 : 0),
                    etb : ($("#ckETB").is(":checked") ? 1 : 0),
                    etc : ($("#ckETC").is(":checked") ? 1 : 0),
                    etd : ($("#ckETD").is(":checked") ? 1 : 0),
                    destination : ($("#ckDestination").is(":checked") ? 1 : 0),
                    agent_name : ($("#ckAgent").is(":checked") ? 1 : 0),
                    shipper_name : ($("#ckShipper").is(":checked") ? 1 : 0),
                    buyer_name : ($("#ckBuyer").is(":checked") ? 1 : 0),
                    branch_name : ($("#ckCabang").is(":checked") ? 1 : 0),
                    notify : ($("#ckNotify").is(":checked") ? 1 : 0),
                    remark : ($("#ckRemark").is(":checked") ? 1 : 0),
                    status_activity : ($("#ckStatusActivity").is(":checked") ? 1 : 0),
                    principal_name : ($("#ckOwner").is(":checked") ? 1 : 0),
                }
                $.ajax({
                    method :"post",
                    url : "<?= base_url("apivessellineup/update_export_set_column_show") ?>",
                    dataType:"json",
                    data  : data,
                    async : false,
                    headers: {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res){
                        if (res.success == 1){
                            ShowNotificationToaster("success", res.message, "Success")
                            $("#form-vlu").submit();
                        } else {
                            ShowNotificationToaster("error", res.message, "Failed")
                        }
                    },
                    error : function(xhr, status, error){
                        var errorMessage = xhr.status + " : " + xhr.statusText;
                        alert("Error - " + errorMessage) 
                    },
                    beforeSend : function(){
                        // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                    },
                    complete : function(){
                    }
                })
            })
            
        })

        function SetDataViewPortList(){
            table_port_list.destroy();
            setTimeout(() => {
                table_port_list = $("#table-port-list").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apiport/gets_master_port") ?>",
                        "type" : "get",
                        "headers" : {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    },
                    "initComplete" : function(settings, json){
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "25px", "targets": [1] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                                return data.port_name;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupPortList(\""+data.port_code+";"+data.port_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                return html;
                            }
                        },
                    ]
                })
            }, 500);
        }

        function afterOpenLookupPortList(params){
            var datas = params.split(";");
            $("#port_code").val(datas[0]);
            $("#port_name").val(datas[1].replace(/`/g, "'"));
            $("#modalPortList").modal("hide")
            SetDataViewAdvanceRequest();
        }

        function check_int(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return (charCode >= 48 && charCode <= 57 || charCode == 8);
        }

        function removeService(id){
            swal({
                title: "Are you sure want to delete it ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        method :"get",
                        url : "<?= base_url("apiadvancerequest/delete_workjob_detail/") ?>"+id,
                        dataType:"json",
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1) {
                                GetsDataService($("#header_id_modal").val());
                                $("#sum_service_value_modal").val((parseFloat(res.sum_service_value)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
                            }
                        },
                        error : function(xhr, status, error){
                            var errorMessage = xhr.status + " : " + xhr.statusText;
                            alert("Error - " + errorMessage) 
                        },
                        beforeSend : function(){
                            // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                        },
                        complete : function(){
                        }
                    })   
                }
            });
        }

        function ShowNotificationToaster(type, message, title) {
            Command: toastr[type](message, title)
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "100",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "swing",
                "showMethod": "slideDown",
                "hideMethod": "slideUp"
            }
        }

        function deleteVesselLineUp(id){
            swal({
                title: "Are you sure want to delete it ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                if (willDelete) {
                    data = {
                        line_up_no : id
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apivessellineup/delete") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 0){
                                ShowNotificationToaster("error", res.message, "Failed")
                            } else {
                                ShowNotificationToaster("success", res.message, "Success")
                                SetDataViewAdvanceRequest();
                            }
                            
                        },
                        error : function(xhr, status, error){
                            var errorMessage = xhr.status + " : " + xhr.statusText;
                            alert("Error - " + errorMessage) 
                        },
                        beforeSend : function(){
                            // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                        },
                        complete : function(){
                            
                        }
                    })
                }
            });
        }

        function SetDataViewAdvanceRequest(){
            // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
            data = {
                branch_code : $("#branch_code").val(),
                dari_tanggal_eta : $("#dari_tanggal_eta").val(),
                sampai_tanggal_eta : $("#sampai_tanggal_eta").val(),
                port_code : $("#port_code").val(),
                cargo_type : $("input[name=radioCoal]:checked").val(),
            }
            $.ajax({
                method :"post",
                url : "<?= base_url("apivessellineup/gets_line_up") ?>",
                data : data,
                dataType:"json",
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    $("#date-last-updated").html(res.date_last_updated);
                    $("#time-last-updated").html(res.time_last_updated);
                    table.destroy();
                    table = $("#table-kegiatan").DataTable({
                        data : res.data,
                        scrollX: true,
                        scrollCollapse: true,
                        autoWidth: true,
                        paging: true,
                        columnDefs: [
                            { "width": "250px", "targets": [0] , className : "text-center"},
                            { "width": "120px", "targets": [1] , className : "text-right"},
                            { "width": "150px", "targets": [2,3,4,5], className : "text-center" },
                            { "width": "250px", "targets": [6], className : "text-center" },
                            { "width": "250px", "targets": [5], className : "text-center" },
                            { "width": "250px", "targets": [7], className : "text-center" },
                            { "width": "250px", "targets": [8], className : "text-center" },
                            { "width": "250px", "targets": [9], className : "text-center" },
                            { "width": "250px", "targets": [10], className : "text-center" },
                            { "width": "25px", "targets": [11], className : "text-center" },
                        ],
                        "columns" : [
                            {data :  null,
                                render : (data) =>{
                                return data.vessel_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.port_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.activity;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.cargo_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.cargo_type;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    return (parseFloat(data.cargo_qty).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.eta;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.etb;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.etc;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.etd;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.destination_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.agent_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.shipper_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                return data.buyer_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    html ='<div class="dropdown">'
                                    html += '<button class="btn btn-ghost" data-toggle="dropdown">'
                                    html += '<span class="fas fa-ellipsis-v"></span>'
                                    html += '</button>'
                                    html += '<div class="dropdown-menu">'
                                    html += "<a class='dropdown-item' href='<?= base_url("vessel-line-up/edit?line_up_no=") ?>"+data.line_up_no+"'><i class='fas fa-edit'></i> &nbsp;&nbsp;Edit</a>";
                                    html += "<button class='dropdown-item' onclick='deleteVesselLineUp("+data.line_up_no+")'><i class='fas fa-trash'></i> &nbsp;&nbsp;Hapus Data</button></div>";
                                    html += '</div>'
                                    html += '</div>'
                                    return html;
                                }
                            },
                        ],
                        "rowCallback" : function(row, data, index){
                            if (data.status_activity == "DEPARTURE"){
                                $("td", row).css("background-color", "#99EDC3")
                            }
                        }
                    })
                },
                error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText;
                    alert("Error - " + errorMessage) 
                },
                beforeSend : function(){
                    // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                },
                complete : function(){
                }
            })
            
        }

        function OpenExportSettingColShow(){
            $("#modalExportSettingColShow").modal("show")
            $.ajax({
                method :"get",
                url : "<?= base_url("apivessellineup/get_export_set_column_show") ?>",
                dataType:"json",
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if (res.vessel_name == 1){
                        $("#ckVessel").prop("checked", true)
                    } else {
                        $("#ckVessel").prop("checked", false)
                    }
                    
                    if (res.port_name == 1){
                        $("#ckPort").prop("checked", true)
                    } else {
                        $("#ckPort").prop("checked", false)
                    }

                    if (res.activity == 1){
                        $("#ckActivity").prop("checked", true)
                    } else {
                        $("#ckActivity").prop("checked", false)
                    }

                    if (res.cargo_name == 1){
                        $("#ckCargo").prop("checked", true)
                    } else {
                        $("#ckCargo").prop("checked", false)
                    }

                    if (res.cargo_type == 1){
                        $("#ckCargoType").prop("checked", true)
                    } else {
                        $("#ckCargoType").prop("checked", false)
                    }

                    if (res.cargo_qty== 1){
                        $("#ckCargoQty").prop("checked", true)
                    } else {
                        $("#ckCargoQty").prop("checked", false)
                    }

                    if (res.eta== 1){
                        $("#ckETA").prop("checked", true)
                    } else {
                        $("#ckETA").prop("checked", false)
                    }

                    if (res.etb== 1){
                        $("#ckETB").prop("checked", true)
                    } else {
                        $("#ckETB").prop("checked", false)
                    }

                    if (res.etc== 1){
                        $("#ckETC").prop("checked", true)
                    } else {
                        $("#ckETC").prop("checked", false)
                    }

                    if (res.etd== 1){
                        $("#ckETD").prop("checked", true)
                    } else {
                        $("#ckETD").prop("checked", false)
                    }

                    if (res.destination== 1){
                        $("#ckDestination").prop("checked", true)
                    } else {
                        $("#ckDestination").prop("checked", false)
                    }

                    if (res.agent_name== 1){
                        $("#ckAgent").prop("checked", true)
                    } else {
                        $("#ckAgent").prop("checked", false)
                    }

                    if (res.shipper_name== 1){
                        $("#ckShipper").prop("checked", true)
                    } else {
                        $("#ckShipper").prop("checked", false)
                    }

                    if (res.buyer_name== 1){
                        $("#ckBuyer").prop("checked", true)
                    } else {
                        $("#ckBuyer").prop("checked", false)
                    }

                    if (res.branch_name== 1){
                        $("#ckCabang").prop("checked", true)
                    } else {
                        $("#ckCabang").prop("checked", false)
                    }

                    if (res.notify== 1){
                        $("#ckNotify").prop("checked", true)
                    } else {
                        $("#ckNotify").prop("checked", false)
                    }

                    if (res.remark== 1){
                        $("#ckRemark").prop("checked", true)
                    } else {
                        $("#ckRemark").prop("checked", false)
                    }

                    if (res.status_activity== 1){
                        $("#ckStatusActivity").prop("checked", true)
                    } else {
                        $("#ckStatusActivity").prop("checked", false)
                    }

                    if (res.principal_name== 1){
                        $("#ckOwner").prop("checked", true)
                    } else {
                        $("#ckOwner").prop("checked", false)
                    }
                },
                error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText;
                    alert("Error - " + errorMessage) 
                },
                beforeSend : function(){
                    // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                },
                complete : function(){
                }
            })
        }
    </script>
    
    <script type="text/javascript">

      
    </script>
  </head>
  <body class="c-app">
    <?php
        $this->load->view("template/sidebar-admin.php");
    ?>
    <div class="c-wrapper c-fixed-components">
        <?php
            $this->load->view("template/topbar-admin.php");
        ?>
        <div class="c-subheader px-3">
          <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item active">Vessel Line Up</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">Vessel Line Up</h4>
                            </div>
                            <div >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-2 <?= ($this->session->user_holding == 1 ? "": "d-none") ?>">
                        <div class="form-group">
                            <label for="branch_code" class="control-label">Cabang</label>
                            <select name="branch_code" id="branch_code" class="form-control ">
                                <option value="">-- Pilih Cabang --</option>
                                <?php
                                    foreach ($data_branch as $branch) {
                                        ?>
                                            <option value="<?= $branch->branch_code ?>" <?= ($branch->branch_code == $this->session->user_kode_cabang ? "selected" : "") ?>><?= $branch->branch_name ?></option>
                                        <?php
                                    }
                                ?>
                            </select>            
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="branch_code" class="control-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="dari_tanggal_eta" value="2023-01-01">        
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="branch_code" class="control-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="sampai_tanggal_eta" value="<?= date("Y-m-d") ?>">        
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <label for="" class="control-label">Port Calling <span class="text-danger">*</span></label>
                                </div>
                                <div>
                                    <button class="btn btn-ghost btn-sm" id="btnBersihkanFilterPort"><small class="text-danger">Bersihkan filter</small><span class="fas fa-times text-danger mx-2"></span></button>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" id="port_name" name="port_name" class="form-control" placeholder="Silahkan input Port" readonly>
                                <input type="hidden" class="form-control"  id="port_code" name="port_code">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="btnPromptPort"><span class="fas fa-search"></span></button>
                                </div>
                                <div class="invalid-feedback" id="invalid-feedback-port-name"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-sm-3 align-item-center">
                        <div class="form-group">
                            <span>Type Cargo</span>
                            <div class="d-flex  mt-3">
                                <div class="flex-fill">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioCoal" id="rbAllCoal"  value="" checked>
                                        <label class="form-check-label" for="rbAllCoal">
                                            Semua
                                        </label>
                                    </div>
                                </div>
                                <div class="flex-fill mx-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioCoal" id="rbCoal" value="COAL" >
                                        <label class="form-check-label" for="rbCoal">
                                            Coal
                                        </label>
                                    </div>
                                </div>
                                <div class="flex-fill w-100 mx-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="radioCoal" id="rbNonCoal"  value="NONCOAL">
                                        <label class="form-check-label" for="rbNonCoal">
                                            Non Coal
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card card-accent-primary">
                            <div class="card-body">
                                <div class="c-chart-wrapper">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="h5">Perubahan Terakhir</span><br>
                                                    <small><i class="fas fa-calendar text-muted"></i><span class="text-muted mx-2" id="date-last-updated">-</span><i class="fas fa-clock text-muted"></i><span class="mx-2 text-muted" id="time-last-updated">-</span></small>
                                                </div>
                                                <div>
                                                    <a href="<?= base_url("vessel-line-up/add") ?>" class="btn btn-outline-primary rounded-pill">Tambah Baru <i class="fas fa-caret-right"></i> </a>
                                                    <div class="btn-group mx-2">
                                                        <button class="btn btn-primary" id="btnExportExcel"><i class="fas fa-file-excel"></i> Export Excel</button>
                                                        <!-- <button class="btn btn-ghost"><i class="fas fa-cog"></i></button> -->
                                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" onclick="OpenExportSettingColShow()"><i class="fas fa-cog"></i><span class="mx-2">Tampilkan Kolom</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="table-kegiatan" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Vessel Name</th>
                                                            <th class="bg-primary text-white">Port Calling</th>
                                                            <th class="bg-primary text-white">Activity</th>
                                                            <th class="bg-primary text-white">Cargo Name</th>
                                                            <th class="bg-primary text-white">Cargo Type</th>
                                                            <th class="bg-primary text-white">Cargo Qty</th>
                                                            <th class="bg-primary text-white">ETA</th>
                                                            <th class="bg-primary text-white">ETB</th>
                                                            <th class="bg-primary text-white">ETC</th>
                                                            <th class="bg-primary text-white">ETD</th>
                                                            <th class="bg-primary text-white">Destination</th>
                                                            <th class="bg-primary text-white">Agent</th>
                                                            <th class="bg-primary text-white">Shipper</th>
                                                            <th class="bg-primary text-white">Buyer</th>
                                                            <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
          </div>
        </div>
        </main>
        <div class="modal " id="modalPortList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                <div class="modal-content" style="border-color:#3c4b64">
                    <div class="modal-body">
                        <table class="table table-striped" id="table-port-list">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Port Name</th>
                                    <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal " id="modalExportSettingColShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
                <div class="modal-content" style="border-color:#3c4b64">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Export Setting</h3>
                                <span class="text-muted">Tampil atau sembunyikan kolom export. <b>On</b> untuk menampilkan dan <b>Off</b> untuk sembunyikan</span>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Vessel Name</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckVessel">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Port Calling</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckPort">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Activity</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckActivity">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Cargo Name</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckCargo">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Cargo Type</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckCargoType">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Cargo Qty</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckCargoQty">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">ETA</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckETA">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">ETB</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckETB">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">ETC</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckETC">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">ETD</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckETD">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Destination</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckDestination">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Agent</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckAgent">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Shipper</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckShipper">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Buyer</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckBuyer">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Cabang</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckCabang">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Notify</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckNotify">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Remark</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckRemark">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Status Activity</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckStatusActivity">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="flex-fill w-100">
                                        <label for="">Owner/Principal</label>    
                                    </div>
                                    <div class="flex-fill w-100">
                                        <label class="c-switch c-switch-pill c-switch-label c-switch-primary">
                                            <input type="checkbox" class="c-switch-input" checked id="ckOwner">
                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" id="btnSimpanExportSettingColShow">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view("template/modal-list-data") ?>
        <?php
            $this->load->view("template/footer-admin");
            $this->load->view("template/modal-logout");
            $this->load->view("template/modal-spinner-please-wait");
        ?>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?= base_url("vendors/@coreui/coreui-pro/js/coreui.bundle.min.js") ?>"></script>
    <!--[if IE]><!-->
    <script src="<?= base_url("vendors/@coreui/icons/js/svgxuse.min.js") ?>"></script>
    <!--<![endif]-->
    <!-- Plugins and scripts required by this view-->
    
    
    <script src="<?= base_url("js/datatables.js") ?>"></script>
    <script src="<?= base_url("vendors/toastr/js/toastr.js")?>"></script>
    <script>
        var table_port_list = $("#table-port-list").DataTable();
        var table = $("#table-kegiatan").DataTable();
    </script>
    
    
  </body>
</html>