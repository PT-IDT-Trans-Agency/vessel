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
    <title>User</title>
    <script type="text/javascript">
        $(document).ready(function(){

            Load();
            function Load(){
                $('#myModalSpinnerPleaseWait').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    method :"get",
                    url : "<?= base_url("apigroup/gets_group") ?>",
                    dataType:"json",
                    async : false,
                    headers: {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res){
                        createHtmlLisGroupAccount(res.data);
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
                    SetDataViewUser();
                    $.ajax({
                        method :"get",
                        url : "<?= base_url("apigroup/gets_group") ?>",
                        dataType:"json",
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            var html = "";
                            res.data.forEach(el => {
                                html += "<option value='"+el.group_code+"'>"+el.group_name+"</option>"
                            });
                            $("#group").html(html);
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
                        setTimeout(() => {
                            $("#hide-spinner").trigger("click");
                        }, 1000);
                    })
                })
            }

            function createHtmlLisGroupAccount(data){
                $("#panel-group-account").empty();
                var html = "";
                data.forEach(el => {
                    html += '<div class="list-group-item list-group-item-accent-primary list-group-item-primary rounded">';
                    html += '<div class="d-flex justify-content-between">';
                    html += '<div>';
                    html += '<strong>'+el.group_name +'</strong><br>';
                    html += '<small class="mt-0">Group Code : '+el.group_code+'</small>';
                    html += '</div>';
                    html += '<div>';
                    html += '<button onclick="OpenFormKonfigurasiAksesGroup(\''+el.group_code+';'+el.group_name+'\')" class="btn btn-outline-primary rounded-pill btn-sm"><i class="fas fa-external-link-square-alt"></i> Konfigurasi Akses</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                });
                $("#panel-group-account").html(html);
            }

                
            
            function SetDataViewUser(){
                table.destroy();
                table = $("#table-user").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apiuser/gets_user") ?>",
                        "type" : "get",
                        "headers" : {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    },
                    "initComplete" : function(settings, json){
                    },
                    scrollX: true,
					scrollCollapse: true,
					autoWidth: true,
					paging: true,
					columnDefs: [
						{ "width": "50px", "targets": [1] },
					],
					"columns" : [
						{data :  null,
							render : (data) =>{
								return data.username;
							}
						},
						{data :  null,
							render : (data) =>{
								id = data.id
								html ='<div class="dropdown">'
								html += '<button class="btn btn-ghost" data-toggle="dropdown">'
								html += '<span class="fas fa-ellipsis-v"></span>'
								html += '</button>'
								html += '<div class="dropdown-menu">'
								html += "<button class='dropdown-item' onclick='OpenFormChangePassword(\""+data.id+"\")'><i class='fas fa-lock text-warning'></i><span class='mx-2'>Change Password</span></button>";
                                html += "<button class='dropdown-item' onclick='deleteUser(\""+data.id+"\")'><i class='fas fa-times text-danger'></i><span class='mx-2'>Delete</span></button>";
								html += '</div>'
								html += '</div>'
								return html;
							}
						},
					]
                })
            }

            function SetDataViewGroupsSaved(){
                table_groups_saved.destroy();
                setTimeout(() => {
                    table_groups_saved = $("#table-groups-saved").DataTable({
                        "ajax" : {
                            "url" : "<?= base_url("apigroup/gets_group") ?>",
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
                            { "width": "25px", "targets": [2] },
                        ],
                        "columns" : [
                            {data :  null,
                                render : (data) =>{
                                    return data.group_code;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    return data.group_name;
                                }
                            },
                            {data :  null,
                                render : (data) =>{
                                    var html = "<button class='btn btn-primary btn-sm' onclick='afterOpenLookupGroupsSaved(\""+data.group_code+";"+data.group_name+"\")'><span class='fas fa-hand-pointer'></span> </button>"
                                    return html;
                                }
                            },
                        ]
                    })
                }, 500);
                
            }

            function ClearFormGroup(){
                $("#group_code").attr("disabled", false);
                $("#lbl-branch-code").html("...")    
                $("#invalid-feedback-group-group-code").empty();
                $("#branch_code").removeClass("form-control is-valid").addClass("form-control");
                $("#branch_code").removeClass("form-control is-invalid").addClass("form-control");
                $("#branch_code").val("");
                $("#group_code").removeClass("form-control is-valid").addClass("form-control");
                $("#group_code").removeClass("form-control is-invalid").addClass("form-control");
                $("#group_code").val("");

                $("#invalid-feedback-group-group-name").empty();
                $("#group_name").removeClass("form-control is-valid").addClass("form-control");
                $("#group_name").removeClass("form-control is-invalid").addClass("form-control");
                $("#group_name").val("");
            }

            function IsValidTextFieldGroup(){
                var retGroupCode = true;
                var retGroupName = true;

                var ret = true;
                if ($("#group_code").val() == "" || $("#group_code").val() == null) {
                    $("#group_code").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-group-group-code").empty();
                    $("#invalid-feedback-group-group-code").append("This Group Code field is required")

                    retGroupCode = false;
                } else {
                    $("#group_code").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-group-group-code").empty();
                    if ($("#branch_code").val() == "" || $("#branch_code").val() == null) {
                        $("#branch_code").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-group-group-code").empty();
                        $("#invalid-feedback-group-group-code").append("This Branch Code field is required")

                        retGroupCode = false;
                    } else {
                        $("#branch_code").removeClass("form-control is-invalid").addClass("form-control is-valid");
                        $("#invalid-feedback-group-group-code").empty();
                        retGroupCode = true;
                    }
                }
                if ($("#group_name").val() == "" || $("#group_name").val() == null) {
                    $("#group_name").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-group-group-name").empty();
                    $("#invalid-feedback-group-group-name").append("This Group Name field is required")

                    retGroupCode = false;
                } else {
                    $("#group_name").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-group-group-name").empty();

                    retGroupCode = true;
                }

                if (retGroupCode == false || retGroupName == false){
                    ret = false;
                }

                return ret;
            }

            function IsValidTextFieldUser(){
                var retUsername = true;
                var retPassword = true;
                var retConfirmPassword = true;

                var ret = true;
                if ($("#username").val() == "" || $("#username").val() == null) {
                    $("#username").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-username").empty();
                    $("#invalid-feedback-username").append("This Username field is required")

                    retUsername = false;
                } else {
                    $("#username").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-username").empty();

                    retUsername = true;
                }

                if ($("#password").val() == "" || $("#password").val() == null) {
                    $("#password").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-password").empty();
                    $("#invalid-feedback-password").append("This Password field is required")

                    retPassword = false;
                } else {
                    $("#password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-password").empty();
                    retPassword = true;
                }

                if ($("#confirm_password").val() == "" || $("#confirm_password").val() == null) {
                    $("#confirm_password").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-confirm-password").empty();
                    $("#invalid-feedback-confirm-password").append("This Confirm Password field is required")

                    retConfirmPassword = false;
                } else {
                    $("#confirm_password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-confirm-password").empty();
                    retConfirmPassword = true;
                }

                if ($("#password").val() != "" && $("#confirm_password").val() != ""){
                    if ($("#password").val() != $("#confirm_password").val()){
                        $("#password").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-password").empty();
                        $("#invalid-feedback-password").append("This Password field doesn't match")

                        $("#confirm_password").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-confirm-password").empty();
                        $("#invalid-feedback-confirm-password").append("This Confirm Password field doesn't match")
                        retPassword = false;
                        retConfirmPassword = false;
                    } else {
                        $("#password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                        $("#invalid-feedback-password").empty();
                        retPassword = true;

                        $("#confirm_password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                        $("#invalid-feedback-confirm-password").empty();
                        retConfirmPassword = true;
                    }
                }

                if (retUsername == false || retPassword == false || retConfirmPassword == false){
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

            function ClearFormUser(){
                $("#invalid-feedback-username").empty();
                $("#username").removeClass("form-control is-valid").addClass("form-control");
                $("#username").removeClass("form-control is-invalid").addClass("form-control");
                $("#username").val("");
                $("#invalid-feedback-password").empty();
                $("#password").removeClass("form-control is-valid").addClass("form-control");
                $("#password").removeClass("form-control is-invalid").addClass("form-control");
                $("#password").val("");
                $("#invalid-feedback-confirm-password").empty();
                $("#confirm_password").removeClass("form-control is-valid").addClass("form-control");
                $("#confirm_password").removeClass("form-control is-invalid").addClass("form-control");
                $("#confirm_password").val("");
            }

            function ClearFormChangePassword(){
                $("#invalid-feedback-new-password").empty();
                $("#new_password").removeClass("form-control is-valid").addClass("form-control");
                $("#new_password").removeClass("form-control is-invalid").addClass("form-control");
                $("#new_password").val("");
                $("#invalid-feedback-confirm-new-password").empty();
                $("#confirm_new_password").removeClass("form-control is-valid").addClass("form-control");
                $("#confirm_new_password").removeClass("form-control is-invalid").addClass("form-control");
                $("#confirm_new_password").val("");
            }

            function IsValidTextFieldChangePassword(){
                var retNewPassword = true;
                var retConfirmNewPassword = true;

                var ret = true;
                if ($("#new_password").val() == "" || $("#new_password").val() == null) {
                    $("#new_password").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-new-password").empty();
                    $("#invalid-feedback-new-password").append("This New Password field is required")

                    retNewPassword = false;
                } else {
                    $("#new_password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-new-password").empty();

                    retNewPassword = true;
                }

                if ($("#confirm_new_password").val() == "" || $("#confirm_new_password").val() == null) {
                    $("#confirm_new_password").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-confirm-new-password").empty();
                    $("#invalid-feedback-confirm-new-password").append("This Confirm Password field is required")

                    retConfirmNewPassword = false;
                } else {
                    $("#confirm_new_password").removeClass("form-control is-invalid").addClass("form-control is-valid");
                    $("#invalid-feedback-confirm-new-password").empty();

                    retConfirmNewPassword = true;
                }

                if ($("#new_password").val() != "" && $("#confirm_new_password").val() != ""){
                    if ($("#new_password").val() != $("#confirm_new_password").val()){
                        $("#new_password").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-new-password").empty();
                        $("#invalid-feedback-new-password").append("This New Password field doesn't match")

                        $("#confirm_new_password").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-confirm-new-password").empty();
                        $("#invalid-feedback-confirm-new-password").append("This Confirm Password field doesn't match")
                        retNewPassword = false;
                        retConfirmNewPassword = false;
                    }
                }

                if (retNewPassword == false || retConfirmNewPassword == false){
                    ret = false;
                }

                return ret;
            }

            //event jquery 
            $("#btnSimpanChangedPassword").click(function(){
                if (!IsValidTextFieldChangePassword()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        id : $("#id_change_password").val(),
                        new_password : $("#new_password").val(),
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apiuser/change_password") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                Load();
                                $("#modalChangePassword").modal("hide")
                                ShowNotificationToaster("success", res.message, "Success")
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

            $("#btnSimpan").click(function(){
                if (!IsValidTextFieldUser()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        username : $("#username").val(),
                        password : $("#password").val(),
                        branch_code : $("#branch").val(),
                        group_code : $("#group").val(),
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apiuser/update_data_user") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                ShowNotificationToaster("success", res.message, "Success")
                                ClearFormUser();
                                Load();
                                $("#modalAksesGroupAdd").modal("hide")
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

            $("#btnAddGroup").click(function(){
                ClearFormGroup();
                $("#modalAksesGroupAdd").modal("show")
            })

            $("#btnGroupsSaved").click(function(){
                SetDataViewGroupsSaved();
                $("#modalGroupsSaved").modal("show")
            })

            $("#btnNewGroup").click(function(){
                ClearFormGroup();
            })

            $("#btnSimpanGroup").click(function(){
                if (!IsValidTextFieldGroup()){
                    ShowNotificationToaster("error", "Data gagal disimpan", "Failed")
                    return false;
                } else {
                    data = {
                        branch_code : $("#branch_code").val(),
                        group_code : $("#group_code").val(),
                        group_name : $("#group_name").val(),
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apigroup/update_group") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                Load();
                                $("#modalAksesGroupAdd").modal("hide")
                                ShowNotificationToaster("success", res.message, "Success")
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

            $("#btnDeleteGroup").click(function(){
                swal({
                    title: "Are you sure want to delete it ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete == true){
                        data = {
                            branch_code : $("#branch_code").val(),
                            group_code : $("#group_code").val(),
                        }
                        $.ajax({
                            method :"post",
                            url : "<?= base_url("apigroup/delete_group") ?>",
                            dataType:"json",
                            data : data,
                            async : false,
                            headers: {
                                "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                            },
                            success:function(res){
                                if (res.success == 1){
                                    Load();
                                    $("#modalAksesGroupAdd").modal("hide")
                                    ShowNotificationToaster("success", res.message, "Success")
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
            })

            $("#branch_code").change(function(){
                if ($(this).val() == ""){
                    $("#lbl-branch-code").html("...")    
                    return false
                }
                $("#lbl-branch-code").html($("#branch_code").val()+".")
            })

            $("#btnSimpanKonfigurasiMenu").click(function(){
                let retList;
                let retCreate;
                let retUpdate;
                let retDelete;
                let retApprove;
                let retPrint;
                if ($("#list").is(":checked") == true){
                    retList = "1"
                } else {
                    retList = "0"
                }

                if ($("#create").is(":checked") == true){
                    retCreate = "1"
                } else {
                    retCreate = "0"
                }

                if ($("#update").is(":checked") == true){
                    retUpdate = "1"
                } else {
                    retUpdate = "0"
                }

                if ($("#delete").is(":checked") == true){
                    retDelete = "1"
                } else {
                    retDelete = "0"
                }

                if ($("#approve").is(":checked") == true){
                    retApprove = "1"
                } else {
                    retApprove = "0"
                }

                if ($("#print").is(":checked") == true){
                    retPrint = "1"
                } else {
                    retPrint = "0"
                }
                data = {
                    recnum : $("#recnum").val(),
                    list : retList,
                    create : retCreate,
                    update : retUpdate,
                    delete : retDelete,
                    approve : retApprove,
                    print : retPrint,
                }
                $.ajax({
                    method :"post",
                    url : "<?= base_url("apigroup/update_konfigurasi_menu") ?>",
                    dataType:"json",
                    data : data,
                    async : false,
                    headers: {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res){
                        if (res.success == 1){
                            $("#modalKonfigurasiMenu").modal("hide")
                            ShowNotificationToaster("success", res.message, "Success")
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
                    table_akses_group.destroy();
                    setTimeout(() => {
                        table_akses_group = $("#table-akses-group").DataTable({
                            "ajax" : {
                                "url" : "<?= base_url("apigroup/gets_group_akses") ?>",
                                "type" : "get",
                                "data" : {
                                    group_code : $("#konfigurasi_menu_group_code").val()
                                },
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
                                { "width": "25px", "targets": [2] },
                            ],
                            "columns" : [
                                {data :  null,
                                    render : (data) =>{
                                        return data.menu_name;
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.list == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.create == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.update == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.delete == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.approve == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        if (data.print == 1){
                                            return "<span class='fas fa-check text-success'></span>"
                                        } else {
                                            return "<span class='fas fa-times text-danger'></span>"
                                        }
                                    }
                                },
                                {data :  null,
                                    render : (data) =>{
                                        var html = "<button class='btn btn-primary btn-sm' onclick='OpenKonfigurasiMenu(\""+data.recnum+"\")'><span class='fas fa-edit'></span> </button>"
                                        return html;
                                    }
                                },
                            ]
                        })
                    }, 500);
                })
            })
        })
    </script>
    
    <script type="text/javascript">
        function ClearFormKonfigurasiGroup(){
            $("#lbl-konfigurasi-group-group-code").empty()
            $("#lbl-konfigurasi-group-group-name").empty()
        }
        function OpenFormKonfigurasiAksesGroup(selectedValue){
            ClearFormKonfigurasiGroup();
            var params = selectedValue.split(";");
            $("#lbl-konfigurasi-group-group-code").html(params[0])
            $("#lbl-konfigurasi-group-group-name").html(params[1])

            table_akses_group.destroy();
            setTimeout(() => {
                table_akses_group = $("#table-akses-group").DataTable({
                    "ajax" : {
                        "url" : "<?= base_url("apigroup/gets_group_akses") ?>",
                        "type" : "get",
                        "data" : {
                            group_code : params[0]
                        },
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
                        { "width": "25px", "targets": [2] },
                    ],
                    "columns" : [
                        {data :  null,
                            render : (data) =>{
                                return data.menu_name;
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.list == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.create == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.update == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.delete == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.approve == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                if (data.print == 1){
                                    return "<span class='fas fa-check text-success'></span>"
                                } else {
                                    return "<span class='fas fa-times text-danger'></span>"
                                }
                            }
                        },
                        {data :  null,
                            render : (data) =>{
                                var html = "<button class='btn btn-primary btn-sm' onclick='OpenKonfigurasiMenu(\""+data.recnum+"\")'><span class='fas fa-edit'></span> </button>"
                                return html;
                            }
                        },
                    ]
                })
            }, 500);
            $("#modalAksesGroupKonfigurasi").modal("show")
        }

        function ClearFormKonfigurasiMenu(){
            $("#lbl-konfigurasi-menu-group-name").html("-");
            $("#lbl-konfigurasi-menu-menu-name").html("-");
            $("#list").prop("checked" ,false);
            $("#create").prop("checked" ,false);
            $("#update").prop("checked" ,false);
            $("#delete").prop("checked" ,false);
            $("#approve").prop("checked" ,false);
            $("#print").prop("checked" ,false);
        }

        function OpenKonfigurasiMenu(id){
            ClearFormKonfigurasiMenu();
            $.ajax({
                method :"get",
                url : "<?= base_url("apigroup/get_group_akses") ?>",
                dataType:"json",
                data : {
                    recnum : id
                },
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    obj = res.data[0];
                    $("#recnum").val(obj.recnum);
                    $("#konfigurasi_menu_group_code").val(obj.group_code);
                    $("#lbl-konfigurasi-menu-group-name").html(obj.group_name);
                    $("#lbl-konfigurasi-menu-menu-name").html(obj.menu_name);
                    if (obj.list == "1"){
                        $("#list").prop("checked" ,true);
                    } else {
                        $("#list").prop("checked" ,false);   
                    }

                    if (obj.create == "1"){
                        $("#create").prop("checked" ,true);
                    } else {
                        $("#create").prop("checked" ,false);   
                    }

                    if (obj.update == "1"){
                        $("#update").prop("checked" ,true);
                    } else {
                        $("#update").prop("checked" ,false);   
                    }

                    if (obj.delete == "1"){
                        $("#delete").prop("checked" ,true);
                    } else {
                        $("#delete").prop("checked" ,false);   
                    }

                    if (obj.approve == "1"){
                        $("#approve").prop("checked" ,true);
                    } else {
                        $("#approve").prop("checked" ,false);   
                    }

                    if (obj.print == "1"){
                        $("#print").prop("checked" ,true);
                    } else {
                        $("#print").prop("checked" ,false);   
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
                $("#modalKonfigurasiMenu").modal("show")
            })
        }

        function ClearFormGroup(){
            $("#group_code").attr("disabled", false);
            $("#lbl-branch-code").html("...")    
            $("#invalid-feedback-group-group-code").empty();
            $("#branch_code").removeClass("form-control is-valid").addClass("form-control");
            $("#branch_code").removeClass("form-control is-invalid").addClass("form-control");
            $("#branch_code").val("");
            $("#group_code").removeClass("form-control is-valid").addClass("form-control");
            $("#group_code").removeClass("form-control is-invalid").addClass("form-control");
            $("#group_code").val("");

            $("#invalid-feedback-group-group-name").empty();
            $("#group_name").removeClass("form-control is-valid").addClass("form-control");
            $("#group_name").removeClass("form-control is-invalid").addClass("form-control");
            $("#group_name").val("");
        }
      
        function afterOpenLookupGroupsSaved(selectedValue){
            var params = selectedValue.split(";");
            ClearFormGroup();
            $("#group_code").attr("disabled", true);
            $("#lbl-branch-code").html(params[0].split(".")[0]+".");
            $("#branch_code").val(params[0].split(".")[0]);
            $("#group_code").val(params[0].split(".")[1]);
            $("#group_name").val(params[1]);
            $("#modalGroupsSaved").modal("hide")
        }

        function SetDataViewUser(){
            table.destroy();
            table = $("#table-user").DataTable({
                "ajax" : {
                    "url" : "<?= base_url("apiuser/gets_user") ?>",
                    "type" : "get",
                    "headers" : {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                },
                "initComplete" : function(settings, json){
                },
                scrollX: true,
                scrollCollapse: true,
                autoWidth: true,
                paging: true,
                columnDefs: [
                    { "width": "50px", "targets": [1] },
                ],
                "columns" : [
                    {data :  null,
                        render : (data) =>{
                            return data.username;
                        }
                    },
                    {data :  null,
                        render : (data) =>{
                            id = data.id
                            html ='<div class="dropdown">'
                            html += '<button class="btn btn-ghost" data-toggle="dropdown">'
                            html += '<span class="fas fa-ellipsis-v"></span>'
                            html += '</button>'
                            html += '<div class="dropdown-menu">'
                            html += "<button class='dropdown-item' onclick='OpenFormLookupEPDA(\""+data.id+"\")'><i class='fas fa-lock text-warning'></i><span class='mx-2'>Change Password</span></button>";
                            html += "<button class='dropdown-item' onclick='deleteUser(\""+data.id+"\")'><i class='fas fa-times text-danger'></i><span class='mx-2'>Delete</span></button>";
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

        function deleteUser(id){
            swal({
                title: "Are you sure want to delete it ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete==true){
                    data = {
                        id : id
                    }
                    $.ajax({
                        method :"post",
                        url : "<?= base_url("apiuser/delete_data_user") ?>",
                        dataType:"json",
                        data : data,
                        async : false,
                        headers: {
                            "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if (res.success == 1){
                                ShowNotificationToaster("success", res.message , "Success")
                                SetDataViewUser();
                            } else {
                                ShowNotificationToaster("error", res.message , "Failed")
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

        function ClearFormChangePassword(){
            $("#invalid-feedback-new-password").empty();
            $("#new_password").removeClass("form-control is-valid").addClass("form-control");
            $("#new_password").removeClass("form-control is-invalid").addClass("form-control");
            $("#new_password").val("");
            $("#invalid-feedback-confirm-new-password").empty();
            $("#confirm_new_password").removeClass("form-control is-valid").addClass("form-control");
            $("#confirm_new_password").removeClass("form-control is-invalid").addClass("form-control");
            $("#confirm_new_password").val("");
        }

        function OpenFormChangePassword(id){
            ClearFormChangePassword();
            $("#id_change_password").val(id)
            $("#modalChangePassword").modal("show")
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
            <li class="breadcrumb-item active">User Role & Menu Setting</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <div>
                        </div>
                        <div>
                            <button class="btn btn-default"><span class="fas fa-bars"></span><span class="mx-2">Tambah Menu</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-accent-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title mb-0 text-primary">Akun Pengguna</h4>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <p class="text-muted">Tambahkan user dan konfigurasikan setelah diinput pada list dibawah form ini.</p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="username">User Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="username" id="username">
                                                <div class="invalid-feedback" id="invalid-feedback-username"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="password" id="password">
                                                <div class="invalid-feedback" id="invalid-feedback-password"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                                                <div class="invalid-feedback" id="invalid-feedback-confirm-password"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="branch">Branch</label>
                                                <select name="branch" id="branch" class="form-control">
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
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="group">Group</label>
                                                <select name="group" id="group" class="form-control">
                                                </select>
                                                <small><span class="text-muted">Hak akses user account secara otomatis tersetting/default sesuai group yang dipilih. Setting group aksesnya disini <a href="">Klik disini.</a></span></small>
                                                <div class="invalid-feedback" id="invalid-feedback-group"></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" type="button" id="btnSimpan" name="btnSimpan"><span class="fas fa-save"></span><span class="mx-2">Simpan</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-accent-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                        <h4 class="card-title mb-0 text-primary">Akun Penggua | Daftar</h4>
                                        </div>
                                    </div>
                                    <div class="c-chart-wrapper" style="margin-top:30px;">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped " id="table-user" width="100%">
                                                        <thead style="font-size:10pt">
                                                            <tr>
                                                                <th class="bg-primary text-white mx-2" style="border-radius: 10px 0 0 0;">Username</th>
                                                                <th class="bg-primary text-white mx-2" style="border-radius: 0 10px 0 0;">#</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="font-size:10pt">
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
                <div class="col-sm-4">
                    <div class="card card-accent-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                <h4 class="card-title mb-0 text-primary">Akun Grup</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <p class="text-muted">Tambahkan grup untuk mengkategorikan akses pengguna</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <div class="list-group list-group-accent" id="panel-group-account">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary w-100 rounded" id="btnAddGroup" name="btnAddGroup" type="button"><span class="fas fa-plus"></span ><span class="mx-2">Manage Group Account</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          </div>
        </div>
        <div class="modal" id="modalAksesGroupAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Manage Group Account</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="group_code">Group Code</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend" id="button-addon3">
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
                                            <span class="input-group-text text-strong text-primary" id="lbl-branch-code">...</span>
                                        </div>
                                        <input type="text" class="form-control" id="group_code" name="group_code" style="text-transform:uppercase">
                                        <div class="dropdown dropleft">
                                            <button class="btn btn-ghost" data-toggle="dropdown">
                                                <span class="fas fa-caret-down"></span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button class="dropdown-item" id="btnNewGroup"><span class="fas fa-plus text-default"></span> <span class="mx-2 text-muted">New Group</span></button>
                                                <button class="dropdown-item" id="btnSimpanGroup"><span class="fas fa-save text-primary"></span> <span class="mx-2 text-primary">Save Group</span></button>
                                                <div class="dropdown-divider"></div>
                                                <button class="dropdown-item" id="btnGroupsSaved"><span class="fas fa-bars text-success"></span> <span class="mx-2 text-success">Groups Saved</span></button>
                                                <button class="dropdown-item" id="btnDeleteGroup" onclick="deleteGroup()"><span class="fas fa-times text-danger"></span> <span class="mx-2 text-danger">Delete Group</span></button>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback" id="invalid-feedback-group-group-code"></div>
                                    </div>
                                </div>        
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="group_name">Group Name</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name">
                                    <div class="invalid-feedback" id="invalid-feedback-group-group-name"></div>
                                </div>        
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalGroupsSaved" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Groups Saved</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-striped " id="table-groups-saved" width="100%">
                                        <thead style="font-size:10pt">
                                            <tr>
                                                <th class="bg-primary text-white mx-2" style="border-radius: 10px 0 0 0;">Group Code</th>
                                                <th class="bg-primary text-white mx-2" >Group Name</th>
                                                <th class="bg-primary text-white mx-2" style="border-radius: 0 10px 0 0;">#</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size:10pt">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal " id="modalAksesGroupKonfigurasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-lg " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Konfigurasi Akses Grup</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Group Description</h4>
                                <div class="c-callout c-callout-default">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <small class="text-muted">Group Code</small><br>
                                            <b><strong class="h6 " id="lbl-konfigurasi-group-group-code">-</strong></b><br>
                                        </div>
                                        <div class="col-sm-12">
                                            <small class="text-muted">Group Name</small><br>
                                            <b><strong class="h6 " id="lbl-konfigurasi-group-group-name">-</strong></b><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12 ">
                                <div class="table-responsive">
                                    <table class="table table-striped " id="table-akses-group" width="100%">
                                        <thead style="font-size:10pt">
                                            <tr>
                                                <th class="bg-primary text-white mx-2" style="border-radius: 10px 0 0 0;">Menu</th>
                                                <th class="bg-primary text-white mx-2">List</th>
                                                <th class="bg-primary text-white mx-2">Create</th>
                                                <th class="bg-primary text-white mx-2">Update</th>
                                                <th class="bg-primary text-white mx-2">Delete</th>
                                                <th class="bg-primary text-white mx-2">Approve</th>
                                                <th class="bg-primary text-white mx-2">Print</th>
                                                <th class="bg-primary text-white mx-2" style="border-radius: 0 10px 0 0;">#</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalKonfigurasiMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-lg " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Konfigurasi Menu</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="text-primary"><span id="lbl-konfigurasi-menu-group-name">-</span> | <span class="text-muted" id="lbl-konfigurasi-menu-menu-name">-</span></h4>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <input type="hidden" id="recnum">
                                <input type="hidden" id="konfigurasi_menu_group_code">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="list" value="1">
                                    <label class="form-check-label" for="list">List</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="create" value="1">
                                    <label class="form-check-label" for="create">Create</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="update" value="1" >
                                    <label class="form-check-label" for="update">Update</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="delete" value="1" >
                                    <label class="form-check-label" for="delete">Delete</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="approve" value="1" >
                                    <label class="form-check-label" for="approve">Approve</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="print" value="1" >
                                    <label class="form-check-label" for="print">Print</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary mx-2" id="btnSimpanKonfigurasiMenu"><span class="fas fa-save"></span ><span class="mx-2">Simpan</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalChangePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-md " role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Change Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="hidden" id="id_change_password">
                                    <input type="password" id="new_password" class="form-control">
                                    <div class="invalid-feedback" id="invalid-feedback-new-password"></div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_new_password">Confirm Password</label>
                                    <input type="password" id="confirm_new_password" class="form-control">
                                    <div class="invalid-feedback" id="invalid-feedback-confirm-new-password"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary mx-2" id="btnSimpanChangedPassword"><span class="fas fa-save"></span ><span class="mx-2">Simpan</span></button>
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
        var table = $("#table-user").DataTable();
        var table_groups_saved = $("#table-groups-saved").DataTable();
        var table_akses_group = $("#table-akses-group").DataTable();
    </script>
    
    
  </body>
</html>
