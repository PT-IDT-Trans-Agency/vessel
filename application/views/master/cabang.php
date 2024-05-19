<!doctype html>
<html lang="en">
  <head>    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url("css/style.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/datatables.net-bs4/css/dataTables.bootstrap4.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/toastr/css/toastr.min.css") ?>" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Cabang</title>
    <script type="text/javascript">
        $(document).ready(function(){
            SetDataViewCabang();
            
            $("#btnSearch").click(function(){
                SetDataViewCabang();
            })

            function SetDataViewCabang(){
                $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});
                table_cabang.destroy();
                table_cabang = $("#table-cabang").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apicabang/gets_cabang") ?>",
                        "type" : "post",
                        "headers" : {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    },
                    "initComplete" : function(settings, json){
                        setTimeout(() => {
                            $("#hide-spinner").trigger("click");
                        }, 1000);
                    },
                    scrollX: true,
                    scrollCollapse: true,
                    autoWidth: false,
                    paging: true,
                    columnDefs: [
                        { "width": "25px", "targets": [2] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                            return data.branch_code;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                            return data.branch_name;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                html ='<div class="dropdown">'
								html += '<button class="btn btn-ghost" data-toggle="dropdown">'
								html += '<span class="fas fa-ellipsis-v"></span>'
								html += '</button>'
								html += '<div class="dropdown-menu">'
                                html += "<button class='dropdown-item' onclick='deleteBranch(\""+data.branch_code+"\")'><i class='fas fa-times text-danger'></i><span class='text-danger mx-2'>Delete</span></button>";
								html += '</div>'
								html += '</div>'
								return html;
                            }
                        },
                    ]
                })
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

            function ClearFormBranch(){
                $("#invalid-feedback-branch-code").empty();
                $("#branch_code").removeClass("form-control is-valid").addClass("form-control");
                $("#branch_code").removeClass("form-control is-invalid").addClass("form-control");
                $("#branch_code").val("");

                $("#invalid-feedback-branch-name").empty();
                $("#branch_name").removeClass("form-control is-valid").addClass("form-control");
                $("#branch_name").removeClass("form-control is-invalid").addClass("form-control");
                $("#branch_name").val("");
            }

            function IsValidTextFieldBranch(){
                var retBranchCode = true;
                var retBranchName = true;

                var ret = true;
                if ($("#branch_code").val() == "" || $("#branch_code").val() == null) {
                    $("#branch_code").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-branch-code").empty();
                    $("#invalid-feedback-branch-code").append("This Branch Code field is required")

                    retBranchCode = false;
                } else {
                    $("#branch_code").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-branch-code").empty();
                    retBranchCode = true;
                }

                if ($("#branch_name").val() == "" || $("#branch_name").val() == null) {
                    $("#branch_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-branch-name").empty();
                    $("#invalid-feedback-branch-name").append("This Branch Name field is required")

                    retBranchName = false;
                } else {
                    $("#branch_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-branch-name").empty();

                    retBranchName = true;
                }

                if (retBranchCode == false || retBranchName == false){
                    ret = false;
                }

                return ret;
            }

            //event jquery
            $("#btnSimpan").click(function(){
                if (!IsValidTextFieldBranch()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        branch_code : $("#branch_code").val(),
                        branch_name : $("#branch_name").val(),
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apicabang/insert_cabang") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                ShowNotificationToaster("success", "Saving data successfully", "Success")
                                ClearFormBranch();
                                SetDataViewCabang();
                            } else {
                                ShowNotificationToaster("error", res.message, "Failed")
                            }
                        },
                        error : function(xhr, status, error){
                            var errorMessage = xhr.status + " : " + xhr.statusText;
                            alert("Error - " + errorMessage)
                        },
                        beforeSend : function(){
                        },
                        complete : function(){
                        }
                    }).done(function(){
                        
                    })
                }
            })

            
        })

        
    </script>
    
    <script type="text/javascript">
        function deleteBranch(branch_code){
            swal({
                title: "Are you sure want to delete it ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete==true){
                    data = {
                        branch_code : branch_code
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apicabang/delete_cabang") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                ShowNotificationToaster("success", "Deleting data successfully", "Success")
                                SetDataViewCabang();
                            } else {
                                ShowNotificationToaster("error", res.message, "Failed")
                            }
                        },
                        error : function(xhr, status, error){
                            var errorMessage = xhr.status + " : " + xhr.statusText;
                            alert("Error - " + errorMessage)
                        },
                        beforeSend : function(){
                        },
                        complete : function(){
                        }
                    }).done(function(){
                        
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

        function SetDataViewCabang(){
            $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});
            table_cabang.destroy();
            table_cabang = $("#table-cabang").DataTable({
                "ajax" : {
                    "url" : "<?= base_url("apicabang/gets_cabang") ?>",
                    "type" : "post",
                    "headers" : {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                },
                "initComplete" : function(settings, json){
                    setTimeout(() => {
                        $("#hide-spinner").trigger("click");
                    }, 1000);
                },
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                paging: true,
                columnDefs: [
                    { "width": "25px", "targets": [2] },
                ],
                "columns" : [
                    {data :  null,
                        render : (data) =>{
                        return data.branch_code;
                        }
                    },
                    {data :  null,
                        render : (data) =>{
                        return data.branch_name;
                        }
                    },
                    {data :  null,
                        render : (data) =>{
                            html ='<div class="dropdown">'
                            html += '<button class="btn btn-ghost" data-toggle="dropdown">'
                            html += '<span class="fas fa-ellipsis-v"></span>'
                            html += '</button>'
                            html += '<div class="dropdown-menu">'
                            html += "<button class='dropdown-item' onclick='deleteUser(\""+data.branch_code+"\")'><i class='fas fa-times text-danger'></i><span class='text-danger mx-2'>Delete</span></button>";
                            html += '</div>'
                            html += '</div>'
                            return html;
                        }
                    },
                ]
            })
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
            <li class="breadcrumb-item active">Branch</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-8">
                    <div class="card card-accent-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                <h4 class="card-title mb-0 text-primary">Branch</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <!-- <div class="col-sm-4">
                                    <img src="<?= base_url("img/ilustrasi-branch.svg") ?>" alt="" class="img-fluid">
                                </div> -->
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="" class="control-form-label">Branch Code <span class="text-danger">*</span></label>
                                                <input type="text" id="branch_code" name="branch_code" class="form-control col-sm-12">
                                                <div class="invalid-feedback" id="invalid-feedback-branch-code"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="" class="control-form-label">Branch Name <span class="text-danger">*</span></label>
                                                <input type="text" id="branch_name" name="branch_name" class="form-control col-sm-12">
                                                <div class="invalid-feedback" id="invalid-feedback-branch-name"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary" id="btnSimpan" type="button"><span class="fas fa-save"></span><span class="mx-2">Simpan</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-8">
                    <div class="card card-accent-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                        <h4 class="card-title mb-0 text-primary">Branch | List</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="table-cabang">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white mx-2" style="border-radius: 10px 0 0 0;">Branch Code</th>
                                                    <th class="bg-primary text-white mx-2">Branch Name</th>
                                                    <th class="bg-primary text-white mx-2" style="border-radius: 0 10px 0 0;">#</th>
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
        </main>
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
    
    <script src="<?= base_url("vendors/datatables.net/js/jquery.dataTables.js") ?>"></script>
    <script src="<?= base_url("vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js") ?>"></script>
    <script src="<?= base_url("js/datatables.js") ?>"></script>
    <script src="<?= base_url("vendors/toastr/js/toastr.js")?>"></script>
    <script>
        var table_cabang = $("#table-cabang").DataTable();
        var table_port = $("#table-port").DataTable();
    </script>
    
    
  </body>
</html>