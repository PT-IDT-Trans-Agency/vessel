<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Form User</title>
    <script type="text/javascript">
        $(document).ready(function(){
            ClearFormKegiatan();

            function ClearFormKegiatan(){
                $("#kode").val("");
                $("#nama_satuan_kerja").val("");
            }

            function IsValidTextFieldFormKegiatan(){
                var retUsername = true;
                var retPassword = true;

                var ret = true;
                if ($("#username").val() == "" || $("#username").val() == null) {
                    $("#username").removeClass("form-control").addClass("form-control is-invalid");
                    $("#invalid-feedback-username").empty();
                    $("#invalid-feedback-username").append("This Username field is required")

                    retUsername = false;
                } else {
                    if (IsDuplicateUsername($("#username").val()) == true){
                        $("#username").removeClass("form-control").addClass("form-control is-invalid");
                        $("#invalid-feedback-username").empty();
                        $("#invalid-feedback-username").append("This Username field is duplicate")

                        retUsername = false;
                    } else {
                        $("#username").removeClass("form-control is-invalid").addClass("form-control is-valid");
                        $("#invalid-feedback-username").empty();

                        retUsername = true;
                    }
                    
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

                if (retUsername == false || retPassword == false ) {
                    ret =false;
                }

                return ret;
            }

            function alertFormPertanggungan(message, status){
                html = '<div class="alert alert-'+ (status == 1 ? "success" : "danger") +'" role="alert">';
                html += '<i class="fas fa-exclamation-triangle"></i> ' + message + '<span type="button" class="close" data-dismiss="alert">x</span>';
                html += '</div>';
                $("#alert-form").empty();
                $("#alert-form").append(html);
            }

            function IsDuplicateUsername(username){
                var ret =false;
                $.ajax({
                    method :"get",
                    url : "<?= base_url("api/is_duplicate_username/") ?>"+username,
                    dataType:"json",
                    async : false,
                    headers: {
                        "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        // console.log(data)
                        if (data.is_duplicate == true) {
                            ret = true;
                        }
                    },
                    error : function(xhr, status, error){
                        var errorMessage = xhr.status + " : " + xhr.statusText;
                        alert("Error - " + errorMessage) 
                    }
                })

                return ret;
            }

            $("#btnSave").click(function(){
                if (!IsValidTextFieldFormKegiatan()){
                    alertFormPertanggungan("Please all correct error", 0);
                } else {
                    $("#form-user").submit();
                }
            })


        
        })

        function check_int(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return (charCode >= 48 && charCode <= 57 || charCode == 8);
        }
    </script>
  </head>
  <body style="background-color:#f2f2f2;">
    <div class="container-fluid" style="margin-top:20px;">
    <div class="row justirfy-content-between">
            <div class="col-sm-2">
                <img class="img-fluid" src="<?= base_url('img/logo.png') ?>" style="width:100px;height:100px;">
            </div>
            <div class="col-sm-6">
                <label for="" style="margin-left:-90px;text-align:center"><b>
                    KEMENTERIAN PERHUBUNGAN REPUBLIK INDONESIA <br>
                    DIREKTORAT JENDERAL PERKERETAAPIAN <br>
                    DIREKTORAT PRASARANA PERKERETAAPIAN <br>
                    </b>
                </label>
            </div>
            <div class="col-sm-4 text-right">
                <?= $this->session->user_name ?> | <a href="<?= base_url("logout")?>">Logout</a>
            </div>
        </div>
        <div class="row text-center" style="margin-top:20px;">
            <div class="col-sm-12">
                <label for=""><b>E-MONITORING KEGIATAN <br> PEMBANGUNAN DAN PENINGKATAN <br> FASILITAS OPERASI KERETA API</b></label>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-2">
                <div class="list-group">
                    <a href="<?= base_url("dashboard") ?>" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="<?= base_url("activity") ?>" class="list-group-item list-group-item-action">Kegiatan</a>
                    <?php
                        if ($this->session->user_type == "ADMIN"){
                            ?>
                                <a href="<?= base_url("user") ?>" class="list-group-item list-group-item-action active">User</a>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header">
                        Form Kegiatan
                    </div>
                    <div class="card-body">
                        <div id="alert-form">
                        </div>
                        <form action="<?= base_url("user/save") ?>" class="form-horizontal" id="form-user" method="post">
                            <div class="form-group row">
                                <label for="" class="col-sm-4">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="username" maxlength="50" name="username">
                                    <div class="invalid-feedback" id="invalid-feedback-username"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-4">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control form-control-sm" id="password" maxlength="50" name="password">
                                    <div class="invalid-feedback" id="invalid-feedback-password"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-4"></label>
                                <div class="col-sm-8">
                                    <button class="btn btn-primary btn-sm" id="btnSave" type="button">Save</button> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>