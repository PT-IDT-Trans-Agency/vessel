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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <title>Form User</title>
    <script type="text/javascript">
        var datas = null;
        $(document).ready(function(){
            var table = $("#table-user").DataTable();
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
                        <div class="row justify-content-between">
                            <div class="col-sm-4">
                                Users
                            </div>
                            <div class="col-sm-4 text-right">
                                <a href="<?= base_url("user/add")?>" class="btn btn-outline-dark btn-sm">Tambah User</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" id="table-user">
                                        <thead>
                                            <tr>
                                                <td>Username</td>
                                                <td width="70px">Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php   
                                                foreach ($data_users as $user) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $user->username?></td>
                                                            <td>
                                                                <a href="<?= base_url("user/edit/".$user->username) ?>" class="btn btn-outline-dark btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                                                <a href="<?= base_url("user/unlink/".$user->username) ?>" onclick="return confirm('Yakin ingin menghapus ?')" class="btn btn-outline-dark btn-sm"><i class="fas fa-trash-alt"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                            ?>      
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
    <?php
        $this->load->view("template/modal-spinner-please-wait.php");
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>