<!doctype html>
<html lang="en">
  <head>    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url("css/style.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/datatables.net-bs4/css/dataTables.bootstrap4.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/toastr/css/toastr.min.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/quill/css/quill.coreui.css") ?>" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
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
            $('#cargo_qty').mask('###,###,###,###,###', {reverse: true});
            $('#gt').mask('###,###,###,###,###', {reverse: true});
            $('#dwt').mask('###,###,###,###,###', {reverse: true});
            $('#nilai_tukar').mask('###,###,###,###,###.00', {reverse: true});
            $('#sum_service_value').mask('###,###,###,###,###.00', {reverse: true});
            $('#service_value_modal').mask('###,###,###,###,###.00', {reverse: true});
            var columnsDefListVesselPrompt = ["Vessel Code","Vessel Name"];
            var columnsDefListPortPrompt = ["Port Code","Port Name"];
            var columnsDefListShipperPrompt = ["Shipper Code","Shipper Name"];
            var columnsDefListPrincipalPrompt = ["Principal Code","Principal Name"];
            var titleTextPrompt = ["Vessel List", "Port List", "Shipper List", "Principal List"];
            var tableNamePrompt = ["vessel", "port", "shipper", "principal"];

            $.ajax({
                method :"get",
                url : "<?= base_url("apivessellineup/get_line_up?line_up_no=".$line_up_no) ?>",
                dataType:"json",
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    $("#line_up_no").val(res.line_up_no);
                    $("#branch_code").val(res.branch_code);
                    $("#time_dep").val(res.time_dep);
                    $("#principal_code").val(res.principal_code);
                    $("#principal_name").val(res.principal_name);
                    $("#shipper_code").val(res.shipper_code);
                    $("#shipper_name").val(res.shipper_name);
                    $("#vessel_code").val(res.vessel_code);
                    $("#vessel_name").val(res.vessel_name);
                    $("#port_code").val(res.port_code);
                    $("#port_name").val(res.port_name);
                    $("#cargo_name").val(res.cargo_name);
                    $("#cargo_qty").val((parseFloat(res.cargo_qty)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
                    $("#destination").val(res.destination);
                },
                error : function(xhr, status, error){
                    var errorMessage = xhr.status + " : " + xhr.statusText;
                    alert("Error - " + errorMessage) 
                    return false;
                },
                beforeSend : function(){
                    // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                },
                complete : function(){
                    
                }
            })

            $("#btnPromptVessel").click(function(){
                OpenLookup("VESSEL");
            })

            $("#btnPromptPort").click(function(){
                OpenLookup("PORT");
            })

            $("#btnPromptShipper").click(function(){
                OpenLookup("SHIPPER");
            })

            $("#btnPromptPrincipal").click(function(){
                OpenLookup("PRINCIPAL");
            })

            function OpenLookup(promptTable){
                var modal = $('#exampleModal');
                modal.modal("show");
                switch (promptTable.toUpperCase()) {
                    case "VESSEL":
                        setAttributeModalListData(titleTextPrompt[0], columnsDefListVesselPrompt, tableNamePrompt[0]);
                        GetDataListVessel();
                        break;
                    case "PORT":
                        setAttributeModalListData(titleTextPrompt[1], columnsDefListPortPrompt, tableNamePrompt[1]);
                        GetDataListPort();
                        break;
                    case "SHIPPER":
                        setAttributeModalListData(titleTextPrompt[2], columnsDefListShipperPrompt, tableNamePrompt[2]);
                        GetDataListShipper();
                        break;
                    case "PRINCIPAL":
                        setAttributeModalListData(titleTextPrompt[3], columnsDefListPrincipalPrompt, tableNamePrompt[3]);
                        GetDataListPrincipal();
                        break;
                }
            }

            function setAttributeModalListData(titleTextPrompt, columnsDefListItemsPrompt, tableNamePrompt){
                $(".modal-title-prompt").text(titleTextPrompt);

                $(".schemaTablePrompt").remove();
                $(".modal-body-prompt").append(createSchemaTablePromptHtml(columnsDefListItemsPrompt,tableNamePrompt));
            }

            function createSchemaTablePromptHtml(columnsDefs,tableName){
                var htmlTextTable = "<div class='schemaTablePrompt'>";
                htmlTextTable += "<div class='table-responsive'>";
                htmlTextTable += "<table class='table table-striped table-bordered datatable dataTable no-footer' id='" + tableName + "'>";
                htmlTextTable += "<thead>";
                htmlTextTable += "<tr>";
                if (tableName == "pertanggungan"){
                    htmlTextTable += "<th width='25'></th>";
                }
                columnsDefs.forEach(indexCol => {
                    htmlTextTable += "<th>"+indexCol+"</th>";
                });
                htmlTextTable += "<th width='25'>#</th>";
                htmlTextTable += "</tr>";
                htmlTextTable += "</thead>";
                htmlTextTable += "<tbody>";
                htmlTextTable += "</tbody>";
                htmlTextTable += "</table>";
                htmlTextTable += "</div>";
                htmlTextTable += "</div>";

                return htmlTextTable;
            }

            function GetDataListVessel(){
                $("#vessel").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apivessel/gets_master_vessel/") ?>",
                        "type" : "get",
                        "headers" : {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "50px", "targets": [0] },
                        { "width": "150px", "targets": [1] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                            return data.vessel_code;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                            return data.vessel_name;
                            }
                        },
                        {data : null,
                            render : (data) =>{
                            var selectedValue = "Vessel;"+data.vessel_code+";"+data.vessel_name;
                            var btnSelected = "<button data-trigger='focus' onclick='AfterOpenLookup(\""+ selectedValue.toString() +"\");' class='btn btn-outline-primary btn-sm'>Select</button>"
                            return btnSelected;
                            }
                        }
                    ],
                })
            }

            function GetDataListPort(){
                $("#port").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apiport/gets_master_port/") ?>",
                        "type" : "get",
                        "headers" : {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "50px", "targets": [0] },
                        { "width": "150px", "targets": [1] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                            return data.port_code;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                            return data.port_name;
                            }
                        },
                        {data : null,
                            render : (data) =>{
                            var selectedValue = "Port;"+data.port_code+";"+data.port_name;
                            var btnSelected = "<button data-trigger='focus' onclick='AfterOpenLookup(\""+ selectedValue.toString() +"\");' class='btn btn-outline-primary btn-sm'>Select</button>"
                            return btnSelected;
                            }
                        }
                    ],
                })
            }

            function GetDataListPrincipal(){
                $("#principal").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apiprincipal/gets_master_principal/") ?>",
                        "type" : "get",
                        "headers" : {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "50px", "targets": [0] },
                        { "width": "150px", "targets": [1] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                            return data.principal_code;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                            return data.principal_name;
                            }
                        },
                        {data : null,
                            render : (data) =>{
                            var selectedValue = "Principal;"+data.principal_code+";"+data.principal_name;
                            var btnSelected = "<button data-trigger='focus' onclick='AfterOpenLookup(\""+ selectedValue.toString() +"\");' class='btn btn-outline-primary btn-sm'>Select</button>"
                            return btnSelected;
                            }
                        }
                    ],
                })
            }

            function GetDataListShipper(){
                $("#shipper").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apishipper/gets_master_shipper/") ?>",
                        "type" : "get",
                        "headers" : {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "50px", "targets": [0] },
                        { "width": "150px", "targets": [1] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                            return data.shipper_code;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                            return data.shipper_name;
                            }
                        },
                        {data : null,
                            render : (data) =>{
                            var selectedValue = "Shipper;"+data.shipper_code+";"+data.shipper_name;
                            var btnSelected = "<button data-trigger='focus' onclick='AfterOpenLookup(\""+ selectedValue.toString() +"\");' class='btn btn-outline-primary btn-sm'>Select</button>"
                            return btnSelected;
                            }
                        }
                    ],
                })
            }

            function IsValidTextField(){
                var retTimeDep = true;
                var retShipperCode = true;
                var retVesselCode = true;
                var retPortCode = true;
                var retPrincipalCode = true;
                var retCargoName = true;
                var retCargoQty = true;
                var retDestination =true;
                var retBranchCode = true;

                var ret = true;
                if ($("#branch_code").val() == "" || $("#branch_code").val() == null) {
                    $("#branch_code").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-branch-code").empty();
                    $("#invalid-feedback-branch-code").append("This Branch Name field is required")

                    retBranchCode = false;
                } else {
                    $("#branch_code").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-branch-code").empty();

                    retBranchCode = true;
                }

                if ($("#shipper_code").val() == "" || $("#shipper_code").val() == null) {
                    $("#shipper_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-shipper-name").empty();
                    $("#invalid-feedback-shipper-name").append("This Shipper field is required")

                    retShipperCode = false;
                } else {
                    $("#shipper_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-shipper-name").empty();

                    retShipperCode = true;
                }

                if ($("#vessel_name").val() == "" || $("#vessel_name").val() == null) {
                    $("#vessel_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-vessel-name").empty();
                    $("#invalid-feedback-vessel-name").append("This Vessel field is required")

                    retVesselCode = false;
                } else {
                    $("#vessel_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-vessel-name").empty();

                    retVesselCode = true;
                }

                if ($("#port_code").val() == "" || $("#port_code").val() == null) {
                    $("#port_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-port-name").empty();
                    $("#invalid-feedback-port-name").append("This Port field is required")

                    retPortCode = false;
                } else {
                    $("#port_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-port-name").empty();

                    retPortCode = true;
                }

                if ($("#principal_code").val() == "" || $("#principal_code").val() == null) {
                    $("#principal_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-principal-name").empty();
                    $("#invalid-feedback-principal-name").append("This Principal field is required")

                    retPrincipalCode = false;
                } else {
                    $("#principal_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-principal-name").empty();

                    retPrincipalCode = true;
                }

                if ($("#cargo_name").val() == "" || $("#cargo_name").val() == null) {
                    $("#cargo_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-cargo-name").empty();
                    $("#invalid-feedback-cargo-name").append("This Cargo Name field is required")

                    retrCargoName = false;
                } else {
                    $("#cargo_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-cargo-name").empty();

                    retrCargoName = true;
                }

                if ($("#cargo_qty").val() == "" || $("#cargo_qty").val() == null) {
                    $("#cargo_qty").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-cargo-qty").empty();
                    $("#invalid-feedback-cargo-qty").append("This Cargo QTY field is required")

                    retCargoQty = false;
                } else {
                    $("#cargo_qty").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-cargo-qty").empty();

                    retCargoQty = true;
                }

                if ($("#time_dep").val() == "" || $("#time_dep").val() == null) {
                    $("#time_dep").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-time-dep").empty();
                    $("#invalid-feedback-time-dep").append("This Time Dep field is required")

                    retTimeDep = false;
                } else {
                    $("#time_dep").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-time-dep").empty();

                    retTimeDep = true;
                }

                if ($("#destination").val() == "" || $("#destination").val() == null) {
                    $("#destination").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-destination").empty();
                    $("#invalid-feedback-destination").append("This Destination field is required")

                    retDestination = false;
                } else {
                    $("#destination").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-destination").empty();

                    retDestination = true;
                }

                if (retVesselCode == false || retDestination == false || retBranchCode == false ||retShipperCode == false || retPortCode == false || retCargoName == false || retCargoQty == false || retPrincipalCode == false) {
                    ret =false;
                }

                return ret;
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

            $("#btnSimpan").click(function(){
                if (!IsValidTextField()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        line_up_no : $("#line_up_no").val(),
                        branch_code : $("#branch_code").val(),
                        time_dep : $("#time_dep").val(),
                        principal_code : $("#principal_code").val(),
                        principal_name : $("#principal_name").val(),
                        shipper_code : $("#shipper_code").val(),
                        shipper_name : $("#shipper_name").val(),
                        vessel_code : $("#vessel_code").val(),
                        vessel_name : $("#vessel_name").val(),
                        port_code : $("#port_code").val(),
                        port_name : $("#port_name").val(),
                        cargo_name : $("#cargo_name").val(),
                        cargo_qty : $("#cargo_qty").val().replace(/,/g, ''),
                        destination : $("#destination").val(),
                    }

                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apivessellineup/update_data_line_up") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            ShowNotificationToaster("success", "Data berhasil disimpan", "Success")
                        },
                        error : function(xhr, status, error){
                            var errorMessage = xhr.status + " : " + xhr.statusText;
                            alert("Error - " + errorMessage) 
                            return false;
                        },
                        beforeSend : function(){
                            // $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});	
                        },
                        complete : function(){
                            
                        }
                    })
                }
            })
        })

        function AfterOpenLookup(selectedValue){
            var datas = selectedValue.split(";");
            switch (datas[0].toUpperCase()) {
                case "VESSEL":
                    $("#vessel_code").val(datas[1]);
                    $("#vessel_name").val(datas[2]);
                    break;
                case "PORT":
                    $("#port_code").val(datas[1]);
                    $("#port_name").val(datas[2]);
                    break;
                case "SHIPPER":
                    $("#shipper_code").val(datas[1]);
                    $("#shipper_name").val(datas[2]);
                    break;
                case "PRINCIPAL":
                    $("#principal_code").val(datas[1]);
                    $("#principal_name").val(datas[2]);
                    break;
            }
            $('#exampleModal').modal('hide');

        }

        function SetDisableAttribute(disabled){
            $("#branch_code").attr("disabled", disabled);
            $("#branch_code").attr("disabled", disabled);
        }

        function check_int(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return (charCode >= 48 && charCode <= 57 || charCode == 8);
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
            <li class="breadcrumb-item"><a href="<?= base_url("vessel-line-up") ?>">Vessel Line Up</a></li>
            <li class="breadcrumb-item active">Add</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                            <h4 class="card-title mb-0">Vessel Line Up</h4>
                            </div>
                        </div>
                        <div class="c-chart-wrapper" style="margin-top:30px;">
                            <form action="<?= base_url("VesselLineUp/save") ?>" method="post">
                                <div class="row" style="margin-top:20px;">
                                    <div class="col-sm-12">
                                        <div class="form-group row  d-none">
                                            <!-- <label for="" class="control-form-label col-sm-2">Vessel Line Up Number</label> -->
                                            <div class="col-sm-4">
                                                <input type="hidden" class="form-control" id="line_up_no" name="line_up_no" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="control-form-label col-sm-2">Branch</label>
                                            <div class="col-sm-4">
                                                <select name="branch_code" id="branch_code" class="form-control">
                                                    <option value="">-- Pilih Cabang --</option>
                                                    <?php
                                                        foreach ($data_branch as $branch) {
                                                            ?>
                                                                <option value="<?= $branch->branch_code ?>"><?= $branch->branch_name ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback" id="invalid-feedback-branch-code"></div>
                                            </div>
                                            <label for="" class="control-form-label col-sm-2">Time Dep.</label>
                                            <div class="col-sm-4">
                                                <input type="date" class="form-control" id="time_dep" name="time_dep" value="<?= date("Y-m-d") ?>">
                                                <div class="invalid-feedback" id="invalid-feedback-time-dep"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="control-form-label col-sm-2">Principal</label>
                                            <div class="col-sm-4">
                                                <div class="input-group mb-3">
                                                    <input type="hidden" class="form-control"  id="principal_code" name="principal_code">
                                                    <input type="text" id="principal_name" name="principal_name" class="form-control" placeholder="Silahkan input Principal" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="btnPromptPrincipal">Search</button>
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-feedback-principal-name"></div>
                                                </div>
                                            </div>
                                            <label for="" class="control-form-label col-sm-2">Shipper</label>
                                            <div class="col-sm-4">
                                                <div class="input-group mb-3">
                                                    <input type="text" id="shipper_name" name="shipper_name" class="form-control" placeholder="Silahkan input Shipper" readonly>
                                                    <input type="hidden" class="form-control"  id="shipper_code" name="shipper_code">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="btnPromptShipper">Search</button>
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-feedback-shipper-name"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="control-form-label col-sm-2">Vessel</label>
                                            <div class="col-sm-4">
                                                <div class="input-group mb-3">
                                                    <input type="hidden" class="form-control"  id="vessel_code" name="vessel_code">
                                                    <input type="text" id="vessel_name" name="vessel_name" class="form-control" placeholder="Silahkan input Vessel" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="btnPromptVessel">Search</button>
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-feedback-vessel-name"></div>
                                                </div>
                                            </div>
                                            <label for="" class="control-form-label col-sm-2">Port of Load</label>
                                            <div class="col-sm-4">
                                                <div class="input-group mb-3">
                                                    <input type="text" id="port_name" name="port_name" class="form-control" placeholder="Silahkan input Port" readonly>
                                                    <input type="hidden" class="form-control"  id="port_code" name="port_code">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="button" id="btnPromptPort">Search</button>
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-feedback-port-name"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="control-form-label col-sm-2">Cargo Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="cargo_name" name="cargo_name" class="form-control">
                                                <div class="invalid-feedback" id="invalid-feedback-cargo-name"></div>
                                            </div>
                                            <label for="" class="control-form-label col-sm-2">Cargo Qty</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="cargo_qty" name="cargo_qty" class="form-control" onchange="return check_int(this)" style="text-align:right;">
                                                <div class="invalid-feedback" id="invalid-feedback-cargo-qty"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="control-form-label col-sm-2">Destination</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="destination" name="destination" class="form-control">
                                                <div class="invalid-feedback" id="invalid-feedback-destination"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn btn-default" id="btnSimpan"><i class="fas fa-save"></i>&nbsp;Simpan</button>
                                            <a href="<?= base_url("vessel-line-up")?>" class="btn btn-danger">Back</a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                <div class="modal-content" style="border-color:#3c4b64">
                    <div class="modal-header" style="background-color:#3c4b64;color:white;">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Add Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" >&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="pertanggungan-item-alert">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_code_modal" id="category_code_modal" class="form-control">
                                <?php
                                    foreach ($data_service_category as $category) {
                                        ?>
                                            <option value="<?= $category->category_code ?>"><?= $category->category_name ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                            <div class="invalid-feedback" id="invalid-feedback-category-code-modal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="control-label">Service<span class="text-danger">*</span></label>
                            <select id="service_code_modal" class="form-control" ></select>
                            <div class="invalid-feedback" id="invalid-feedback-service-code-modal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Details</label>
                            <div id="editor"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Currency</label>
                            <select name="currency_code_modal" id="currency_code_modal" class="form-control">
                                <?php
                                    foreach ($data_currency as $currency) {
                                        ?>
                                            <option value="<?= $currency->currency_code ?>"><?= $currency->currency_code ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Amount</label>
                            <input type="text" id="service_value_modal" name="service_value_modal" class="form-control" style="text-align:right;">
                            <div class="invalid-feedback" id="invalid-feedback-service-value-modal"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Remarks</label>
                            <textarea name="remarks_modal" id="remarks_modal" cols="30" rows="5" class="form-control"></textarea>
                            <div class="invalid-feedback" id="invalid-feedback-remarks-modal"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="btnAddItem" style="background-color:#3c4b64;color:white;border-color:#3c4b64">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        </main>
        <?php $this->load->view("template/modal-list-data") ?>
        <?php
            $this->load->view("template/footer-admin");
            $this->load->view("template/modal-logout");
        ?>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?= base_url("vendors/@coreui/coreui-pro/js/coreui.bundle.min.js") ?>"></script>
    <!--[if IE]><!-->
    <script src="<?= base_url("vendors/@coreui/icons/js/svgxuse.min.js") ?>"></script>
    <!--<![endif]-->
    <!-- Plugins and scripts required by this view-->
    
    <script src="<?= base_url("vendors/datatables.net/js/jquery.dataTables.js") ?>"></script>
    <script src="<?= base_url("vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js") ?>"></script>
    <script src="<?= base_url("js/datatables.js") ?>"></script>
    <script src="<?= base_url("vendors/toastr/js/toastr.js")?>"></script>
    <script src="<?= base_url("vendors/quill/js/quill.min.js") ?>"></script>
    <script src="<?= base_url("js/text-editor.js") ?>"></script>
    <script>
        var table_service = $("#table-service").DataTable();
    </script>
    
    
  </body>
</html>