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
        $(document).ready(function(){
            $('#cargo_qty').mask('###,###,###,###,###', {reverse: true});
            Load();
            function Load(){
                var line_up_no = "<?= $line_up_no ?>"
                $.ajax({
                    method :"get",
                    url : "<?= base_url("apivessellineup/get_line_up?line_up_no=") ?>"+line_up_no,
                    dataType:"json",
                    async : false,
                    headers: {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res){
                        if (res.exists == 1){
                            $("#line_up_no").val("<?= $line_up_no ?>")
                            $("#branch_code").val(res.branch_code);
                            $("#eta").val(res.eta);
                            $("#etb").val(res.etb);
                            $("#etc").val(res.etc);
                            $("#etd").val(res.etd);
                            $("#shipper_code").val(res.shipper_code);
                            $("#shipper_name").val(res.shipper_name);
                            $("#vessel_code").val(res.vessel_code);
                            $("#vessel_name").val(res.vessel_name);
                            $("#port_code").val(res.port_code);
                            $("#port_name").val(res.port_name);
                            $("#owner").val(res.owner);
                            $("#cargo_name").val(res.cargo_name);
                            $("#cargo_qty").val(parseFloat(res.cargo_qty).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                            $("#destination_code").val(res.destination_code);
                            $("#destination_name").val(res.destination_name);
                            $("#agent_code").val(res.agent_code);
                            $("#agent_name").val(res.agent_name);
                            $("#pbm_code").val(res.pbm_code);
                            $("#pbm_name").val(res.pbm_name);
                            $("#buyer_code").val(res.buyer_code);
                            $("#buyer_name").val(res.buyer_name);
                            if (res.activity == "LOAD"){
                                $("#rbLoad").prop("checked", true);
                            } else {
                                $("#rbDischarge").prop("checked", true);
                            }
                            $("#notify").val(res.notify);
                            $("#remark").val(res.remark);
                            $("#status_activity").val(res.status_activity);
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

            function SetDataViewDestinationList(){
                table_destination_list.destroy();
                setTimeout(() => {
                    table_destination_list = $("#table-destination-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apidestination/gets_master_destination") ?>",
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
                                    return data.destination_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupDestinationList(\""+data.destination_code+";"+data.destination_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function SetDataViewAgentList(){
                table_agent_list.destroy();
                setTimeout(() => {
                    table_agent_list = $("#table-agent-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apiagent/gets_master_agent") ?>",
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
                                    return data.agent_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupAgentList(\""+data.agent_code+";"+data.agent_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function SetDataViewPBMList(){
                table_pbm_list.destroy();
                setTimeout(() => {
                    table_pbm_list = $("#table-pbm-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apipbm/gets_master_pbm") ?>",
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
                                    return data.pbm_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupPBMList(\""+data.pbm_code+";"+data.pbm_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function SetDataViewShipperList(){
                table_shipper_list.destroy();
                setTimeout(() => {
                    table_shipper_list = $("#table-shipper-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apishipper/gets_master_shipper") ?>",
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
                                    return data.shipper_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupShipperList(\""+data.shipper_code+";"+data.shipper_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function SetDataViewBuyerList(){
                table_buyer_list.destroy();
                setTimeout(() => {
                    table_buyer_list = $("#table-buyer-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apibuyer/gets_master_buyer") ?>",
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
                                    return data.buyer_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupBuyerList(\""+data.buyer_code+";"+data.buyer_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function SetDataViewVesselList(){
                table_vessel_list.destroy();
                setTimeout(() => {
                    table_vessel_list = $("#table-vessel-list").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apivessel/gets_master_vessel") ?>",
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
                                    return data.vessel_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupVesselList(\""+data.vessel_code+";"+data.vessel_name.replace(/'/g, "`")+"\")'><span class='fas fa-edit'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
            }

            function IsValidTextField(){
                var retBranchCode = true;
                var retPortName = true;
                var retDestinationName = true;
                var retAgentName = true;
                var retPBMName = true;
                var retShipperName = true;
                var retBuyerName = true;
                var retVesselName = true;
                var retOwner = true;
                var retNotify = true;
                var retCargoName = true;
                var retCargoQty = true;
                var retETA = true;
                var retETB = true;
                var retETC = true;
                var retETD = true;

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

                if ($("#port_name").val() == "" || $("#port_name").val() == null) {
                    $("#port_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-port-name").empty();
                    $("#invalid-feedback-port-name").append("This Port of Load field is required")

                    retPortName = false;
                } else {
                    $("#port_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-port-name").empty();

                    retPortName = true;
                }

                if ($("#destination_name").val() == "" || $("#destination_name").val() == null) {
                    $("#destination_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-destination").empty();
                    $("#invalid-feedback-destination").append("This Destination field is required")

                    retDestinationName = false;
                } else {
                    $("#destination_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-destination").empty();

                    retDestinationName = true;
                }

                if ($("#agent_name").val() == "" || $("#agent_name").val() == null) {
                    $("#agent_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-agent-name").empty();
                    $("#invalid-feedback-agent-name").append("This Agent field is required")

                    retAgentName = false;
                } else {
                    $("#agent_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-agent-name").empty();

                    retAgentName = true;
                }

                if ($("#pbm_name").val() == "" || $("#pbm_name").val() == null) {
                    $("#pbm_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-pbm-name").empty();
                    $("#invalid-feedback-pbm-name").append("This PBM field is required")

                    retPBMName = false;
                } else {
                    $("#pbm_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-pbm-name").empty();

                    retPBMName = true;
                }

                if ($("#shipper_name").val() == "" || $("#shipper_name").val() == null) {
                    $("#shipper_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-shipper-name").empty();
                    $("#invalid-feedback-shipper-name").append("This Shipper field is required")

                    retShipperName = false;
                } else {
                    $("#shipper_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-shipper-name").empty();

                    retShipperName = true;
                }

                if ($("#buyer_name").val() == "" || $("#buyer_name").val() == null) {
                    $("#buyer_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-buyer-name").empty();
                    $("#invalid-feedback-buyer-name").append("This Buyer field is required")

                    retBuyerName = false;
                } else {
                    $("#buyer_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-buyer-name").empty();

                    retBuyerName = true;
                }

                if ($("#vessel_name").val() == "" || $("#vessel_name").val() == null) {
                    $("#vessel_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-vessel-name").empty();
                    $("#invalid-feedback-vessel-name").append("This Vessel field is required")

                    retVesselName = false;
                } else {
                    $("#vessel_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-vessel-name").empty();

                    retVesselName = true;
                }

                if ($("#owner").val() == "" || $("#owner").val() == null) {
                    $("#owner").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-owner").empty();
                    $("#invalid-feedback-owner").append("This Owner field is required")

                    retOwner = false;
                } else {
                    $("#owner").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-owner").empty();

                    retOwner = true;
                }

                if ($("#notify").val() == "" || $("#notify").val() == null) {
                    $("#notify").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-notify").empty();
                    $("#invalid-feedback-notify").append("This Notify field is required")

                    retNotify = false;
                } else {
                    $("#notify").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-notify").empty();

                    retNotify = true;
                }

                if ($("#cargo_name").val() == "" || $("#cargo_name").val() == null) {
                    $("#cargo_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-cargo-name").empty();
                    $("#invalid-feedback-cargo-name").append("This Cargo Name field is required")

                    retCargoName = false;
                } else {
                    $("#cargo_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-cargo-name").empty();

                    retCargoName = true;
                }

                if ($("#cargo_qty").val() == "" || $("#cargo_qty").val() == null) {
                    $("#cargo_qty").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-cargo-qty").empty();
                    $("#invalid-feedback-cargo-qty").append("This Cargo Qty field is required")

                    retCargoQty = false;
                } else {
                    $("#cargo_qty").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-cargo-qty").empty();

                    retCargoQty = true;
                }

                if ($("#eta").val() == "" || $("#eta").val() == null) {
                    $("#eta").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-eta").empty();
                    $("#invalid-feedback-eta").append("This Cargo Qty field is required")

                    retETA = false;
                } else {
                    $("#eta").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-eta").empty();

                    retETA = true;
                }

                if ($("#etb").val() == "" || $("#etb").val() == null) {
                    $("#etb").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-etb").empty();
                    $("#invalid-feedback-etb").append("This ETB field is required")

                    retETB = false;
                } else {
                    $("#etb").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-etb").empty();

                    retETB = true;
                }

                if ($("#etc").val() == "" || $("#etc").val() == null) {
                    $("#etc").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-etc").empty();
                    $("#invalid-feedback-etc").append("This ETC field is required")

                    retETC = false;
                } else {
                    $("#etc").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-etc").empty();

                    retETC = true;
                }

                if ($("#etd").val() == "" || $("#etd").val() == null) {
                    $("#etd").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-etd").empty();
                    $("#invalid-feedback-etd").append("This ETD field is required")

                    retETD = false;
                } else {
                    $("#etd").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-etd").empty();

                    retETD = true;
                }

                if (retBranchCode == false || retPortName == false ||  retDestinationName == false || retAgentName == false || retPBMName == false 
                    || retShipperName == false || 
                    retBuyerName == false || 
                    retVesselName == false || retOwner == false || retNotify == false 
                    || retCargoName == false || retCargoQty == false || retETA == false || retETB == false || retETC == false || retETD == false
                ){
                    ret = false;
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

            
            //event jquery
            $("#btnPromptPort").click(function(){
                SetDataViewPortList();
                $("#modalPortList").modal("show")
            })
            $("#btnPromptDestination").click(function(){
                SetDataViewDestinationList();
                $("#modalDestinationList").modal("show")
            })
            $("#btnPromptAgent").click(function(){
                SetDataViewAgentList()
                $("#modalAgentList").modal("show")
            })
            $("#btnPromptPBM").click(function(){
                SetDataViewPBMList();
                $("#modalPBMList").modal("show")
            })
            $("#btnPromptShipper").click(function(){
                SetDataViewShipperList();
                $("#modalShipperList").modal("show")
            })
            $("#btnPromptBuyer").click(function(){
                SetDataViewBuyerList();
                $("#modalBuyerList").modal("show")
            })
            $("#btnPromptVessel").click(function(){
                SetDataViewVesselList();
                $("#modalVesselList").modal("show")
            })

            $("#btnSimpan").click(function(){
                if(!IsValidTextField()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        line_up_no : $("#line_up_no").val(),
                        eta : $("#eta").val().replace("T", " "),
                        etb : $("#etb").val().replace("T", " "),
                        etc : $("#etc").val().replace("T", " "),
                        etd : $("#etd").val().replace("T", " "),
                        branch_code : $("#branch_code").val(),
                        shipper_code : $("#shipper_code").val(),
                        shipper_name : $("#shipper_name").val(),
                        vessel_code : $("#vessel_code").val(),
                        vessel_name : $("#vessel_name").val(),
                        port_code : $("#port_code").val(),
                        port_name : $("#port_name").val(),
                        owner : $("#owner").val(),
                        cargo_name : $("#cargo_name").val(),
                        cargo_type : $("input[name=radioCoal]:checked").val(),
                        cargo_qty : $("#cargo_qty").val().replace(/,/g, ''),
                        destination_code : $("#destination_code").val(),
                        destination_name : $("#destination_name").val(),
                        agent_code : $("#agent_code").val(),
                        agent_name : $("#agent_name").val(),
                        pbm_code : $("#pbm_code").val(),
                        pbm_name : $("#pbm_name").val(),
                        // buyer_code : $("#buyer_code").val(),
                        buyer_code : "-",
                        buyer_name : $("#buyer_name").val(),
                        activity : $("input[name=radioActivity]:checked").val(),
                        notify : $("#notify").val(),
                        remark : $("#remark").val(),
                        status_activity : $("#status_activity").val(),
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
    </script>
    
    <script type="text/javascript">
        function check_int(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return (charCode >= 48 && charCode <= 57 || charCode == 8);
        }

        function afterOpenLookupPortList(params){
            var datas = params.split(";");
            $("#port_code").val(datas[0]);
            $("#port_name").val(datas[1].replace(/`/g, "'"));
            $("#modalPortList").modal("hide")
        }

        function afterOpenLookupDestinationList(params){
            var datas = params.split(";");
            $("#destination_code").val(datas[0]);
            $("#destination_name").val(datas[1].replace(/`/g, "'"));
            $("#modalDestinationList").modal("hide")
        }

        function afterOpenLookupAgentList(params){
            var datas = params.split(";");
            $("#agent_code").val(datas[0]);
            $("#agent_name").val(datas[1].replace(/`/g, "'"));
            $("#modalAgentList").modal("hide")
        }

        function afterOpenLookupPBMList(params){
            var datas = params.split(";");
            $("#pbm_code").val(datas[0]);
            $("#pbm_name").val(datas[1].replace(/`/g, "'"));
            $("#modalPBMList").modal("hide")
        }

        function afterOpenLookupShipperList(params){
            var datas = params.split(";");
            $("#shipper_code").val(datas[0]);
            $("#shipper_name").val(datas[1].replace(/`/g, "'"));
            $("#modalShipperList").modal("hide")
        }

        function afterOpenLookupBuyerList(params){
            var datas = params.split(";");
            $("#buyer_code").val(datas[0]);
            $("#buyer_name").val(datas[1].replace(/`/g, "'"));
            $("#modalBuyerList").modal("hide")
        }

        function afterOpenLookupVesselList(params){
            var datas = params.split(";");
            $("#vessel_code").val(datas[0]);
            $("#vessel_name").val(datas[1].replace(/`/g, "'"));
            $("#modalVesselList").modal("hide")
        }
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
                        <h4 class="card-title mb-0">Vessel Line Up</h4>
                        <span class="text-muted small">Lengkapi setiap bagian form di bawah ini.</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <a href="<?= base_url("vessel-line-up") ?>" class="btn btn-default"><span class="fas fa-caret-left"></span><span class="mx-2">Kembali ke daftar</span></a>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-8">
                        <div class="card card-accent-primary">
                            <div class="card-body">
                                <div class="c-chart-wrapper">
                                    <form action="<?= base_url("VesselLineUp/save") ?>" method="post" id="form-vlu">
                                        <div class="row">
                                            <div class="col-sm-4 <?= ($this->session->branch_type == 1? "" : "d-none") ?>">
                                                <div class="form-group">
                                                    <label for="branch_code" class="control-label">Branch <span class="text-danger">*</span></label>
                                                    <input type="hidden" class="form-control" id="line_up_no" name="line_up_no" >
                                                    <select name="branch_code" id="branch_code" class="form-control ">
                                                        <option value="">-- Pilih Cabang --</option>
                                                        <?php
                                                            foreach ($data_branch as $branch) {
                                                                ?>
                                                                    <option value="<?= $branch->branch_code ?>" <?= ($this->session->user_kode_cabang == $branch->branch_code ? "selected" : "") ?>><?= $branch->branch_name ?></option>
                                                                <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <div class="invalid-feedback" id="invalid-feedback-branch-code"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="" class="control-label">Port Calling <span class="text-danger">*</span></label>
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
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="destination_id" class="control-label">Destination <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="destination_name" name="destination_name" class="form-control" placeholder="Silahkan input Destination" readonly>
                                                        <input type="hidden" class="form-control"  id="destination_code" name="destination_code">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptDestination"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-destination"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="agent_name" class="control-label">Agent <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="agent_name" name="agent_name" class="form-control" placeholder="Silahkan input Agent" readonly>
                                                        <input type="hidden" class="form-control"  id="agent_code" name="agent_code">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptAgent"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-agent-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="pbm" class="control-label">Perusahaan Bongkar Muat (PBM) <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="pbm_name" name="pbm_name" class="form-control" placeholder="Silahkan input PBM" readonly>
                                                        <input type="hidden" class="form-control"  id="pbm_code" name="pbm_code">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptPBM"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-pbm-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 d-none">
                                                <label for="" class="control-form-label col-sm-2 ">Principal <span class="text-danger">*</span></label>
                                                <div class="col-sm-4 d-none">
                                                    <div class="input-group mb-3">
                                                        <input type="hidden" class="form-control"  id="principal_code" name="principal_code">
                                                        <input type="text" id="principal_name" name="principal_name" class="form-control" placeholder="Silahkan input Principal" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" id="btnPromptPrincipal">Search</button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-principal-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="shipper_name" class="control-label">Shipper <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="shipper_name" name="shipper_name" class="form-control" placeholder="Silahkan input Shipper" readonly>
                                                        <input type="hidden" class="form-control"  id="shipper_code" name="shipper_code">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptShipper"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-shipper-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="buyer_name" class="control-label">Buyer <span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_name" name="buyer_name" class="form-control">
                                                    <div class="invalid-feedback" id="invalid-feedback-buyer-name"></div>
                                                    <!-- <div class="input-group mb-3">
                                                        <input type="text" id="buyer_name" name="buyer_name" class="form-control" placeholder="Silahkan input Buyer" readonly>
                                                        <input type="hidden" class="form-control"  id="buyer_code" name="buyer_code">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptBuyer"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-buyer-name"></div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="owner" class="control-label">Owner/Principal</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="owner" name="owner">
                                                        <div class="invalid-feedback" id="invalid-feedback-owner"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="notify" class="control-label">Notify</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="notify" name="notify">
                                                        <div class="invalid-feedback" id="invalid-feedback-notify"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="remark" class="control-label">Keterangan</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="remark" name="remark">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="remark" class="control-label">Status Activity</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-flag"></i></span>
                                                        </div>
                                                        <select name="status_activity" id="status_activity" class="form-control">
                                                            <option value="ONPROGRESS">On Progress</option>
                                                            <option value="COMPLETE">Complete</option>
                                                            <option value="FOURTHCOMING">Fourthcoming</option>
                                                            <option value="ANCHORAGE">Anchorage</option>
                                                            <option value="DEPARTURE">Departure</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-sm-12">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-outline-primary rounded-pill" id="btnSimpan">Simpan<span class="mx-2 fas fa-save"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card card-accent-primary">
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-vessel-tab" data-toggle="pill" href="#pills-vessel" role="tab" aria-controls="pills-vessel" aria-selected="true"><span class="fas fa-bars"></span> <span class="mx-2">Vessel</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-estimate-time-tab" data-toggle="pill" href="#pills-estimate-time" role="tab" aria-controls="pills-estimate-time" aria-selected="false"><span class="fas fa-calendar"></span> <span class="mx-2">Estimate Time</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-vessel" role="tabpanel" aria-labelledby="pills-vessel-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="vessel_name" class="control-label">Vessel <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <input type="hidden" class="form-control"  id="vessel_code" name="vessel_code">
                                                        <input type="text" id="vessel_name" name="vessel_name" class="form-control" placeholder="Silahkan input Vessel" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button" id="btnPromptVessel"><span class="fas fa-search"></span></button>
                                                        </div>
                                                        <div class="invalid-feedback" id="invalid-feedback-vessel-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <span>Activity</span>
                                                    <div class="d-flex">
                                                        <div class="flex-fill">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="radioActivity" id="rbLoad" value="LOAD" checked>
                                                                <label class="form-check-label" for="rbLoad">
                                                                    Load
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="flex-fill w-100 mx-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="radioActivity" id="rbDischarge"  value="DISCHARGE">
                                                                <label class="form-check-label" for="rbDischarge">
                                                                    Discharge
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <span>Type Cargo</span>
                                                    <div class="d-flex">
                                                        <div class="flex-fill">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="radioCoal" id="rbCoal" value="COAL" checked>
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
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="cargo_name" class="control-label">Cargo Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <input type="text" id="cargo_name" name="cargo_name" class="form-control">
                                                        <div class="invalid-feedback" id="invalid-feedback-cargo-name"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="cargo_qty" class="control-label">Cargo Qty <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-edit"></i></span>
                                                        </div>
                                                        <input type="text" id="cargo_qty" name="cargo_qty" class="form-control" style="text-align:right;">
                                                        <div class="invalid-feedback" id="invalid-feedback-cargo-qty"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-estimate-time" role="tabpanel" aria-labelledby="pills-estimate-time-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="eta" class="control-label">ETA <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="datetime-local" class="form-control" id="eta" name="eta" value="<?= date("Y-m-d H:i:s") ?>">
                                                        <div class="invalid-feedback" id="invalid-feedback-eta"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="etb" class="control-label">ETB <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="datetime-local" class="form-control" id="etb" name="etb" value="<?= date("Y-m-d H:i:s") ?>">
                                                        <div class="invalid-feedback" id="invalid-feedback-etb"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="etc" class="control-label">ETC <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="datetime-local" class="form-control" id="etc" name="etc" value="<?= date("Y-m-d H:i:s") ?>">
                                                        <div class="invalid-feedback" id="invalid-feedback-etc"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="etd" class="control-label">ETD <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="addon-wrapping"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="datetime-local" class="form-control" id="etd" name="etd" value="<?= date("Y-m-d H:i:s") ?>">
                                                        <div class="invalid-feedback" id="invalid-feedback-etd"></div>
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
          </div>
          <div class="modal " id="modalDestinationList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                <div class="modal-content" style="border-color:#3c4b64">
                    <div class="modal-body">
                        <table class="table table-striped" id="table-destination-list">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Destination Name</th>
                                    <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalAgentList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                    <div class="modal-content" style="border-color:#3c4b64">
                        <div class="modal-body bg-transparent">
                            <table class="table table-striped" id="table-agent-list">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Agent Name</th>
                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modalPBMList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                    <div class="modal-content" style="border-color:#3c4b64">
                        <div class="modal-body bg-transparent">
                            <table class="table table-striped" id="table-pbm-list">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Perusahaan Bongkar Muat</th>
                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal " id="modalShipperList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                    <div class="modal-content" style="border-color:#3c4b64">
                        <div class="modal-body bg-transparent">
                            <table class="table table-striped" id="table-shipper-list">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Shipper Name</th>
                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal " id="modalShipperList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                    <div class="modal-content" style="border-color:#3c4b64">
                        <div class="modal-body bg-transparent">
                            <table class="table table-striped" id="table-shipper-list">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Shipper Name</th>
                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal " id="modalBuyerList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                    <div class="modal-content" style="border-color:#3c4b64">
                        <div class="modal-body bg-transparent">
                            <table class="table table-striped" id="table-buyer-list">
                                <thead>
                                    <tr>
                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Buyer Name</th>
                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
        <div class="modal " id="modalVesselList" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md" role="document">
                <div class="modal-content" style="border-color:#3c4b64">
                    <div class="modal-body">
                        <table class="table table-striped" id="table-vessel-list">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px;">Vessel Name</th>
                                    <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;">#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
        var table_port_list = $("#table-port-list").DataTable();
        var table_destination_list = $("#table-destination-list").DataTable();
        var table_agent_list = $("#table-agent-list").DataTable();
        var table_pbm_list = $("#table-pbm-list").DataTable();
        var table_shipper_list = $("#table-shipper-list").DataTable();
        var table_buyer_list = $("#table-buyer-list").DataTable();
        var table_vessel_list = $("#table-vessel-list").DataTable();
    </script>
    
    
  </body>
</html>