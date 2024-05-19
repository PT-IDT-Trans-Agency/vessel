<!doctype html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?= base_url("css/style.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/datatables.net-bs4/css/dataTables.bootstrap4.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/toastr/css/toastr.min.css") ?>" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Login</title>
  </head>
  <body  class="c-app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <?php if (isset($pesan)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <h4>Warning!</h4>
                        <?php echo $pesan; ?>
                    </div>
                <?php } else ?>
                <form action="<?= base_url("login/post") ?>" method="POST" enctype="multipart/form-data">
                    <div class="card-group" style="">
                        <div class="card shadow-lg bg-white rounded" style="background : url('<?= base_url("img/background-login.jpeg") ?>');">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="p-1 flex-fill w-40 text-center">
                                        <img src="<?= base_url("img/asuradur.svg")?>" class="img-fluid" alt="Responsive image" style="height:200px">
                                        
                                    </div>
                                    <div class="p-1 mt-5 flex-fill w-60">
                                        <h3 class="text-start"><b>PT IDT Trans Agency</b></h3>
                                        <h6 class="text-start"><b>Portal Line Up</b></h6>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-primary">
                                                        Cabang
                                                    </span>
                                                </div>
                                                <select name="branch_code" id="branch_code" class="form-control">
                                                    <?php
                                                        foreach ($data_cabang as $cabang) {
                                                            ?>
                                                                <option value="<?= $cabang->branch_code ?>"><?= $cabang->branch_name ?></option>
                                                            <?php
                                                        }
                                                        
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">
                                                    <i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="username" value="<?= $username ?>">
                                            </div>
                                            <div class="text-danger" id="invalid-feedback-username"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">  
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-key"></i>
                                                    </span>
                                                </div>
                                                <input type="password" class="form-control" name="password" value="<?= $password ?>">
                                            </div>
                                            <div class="text-danger" id="invalid-feedback-password"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                    </div>
                                                    <div>
                                                        <!-- <button class="btn btn-primary px-4 bg-primary rounded-pill text-white" type="button" id="btnLogin"><i class="fas fa-sign-in-alt"></i> Login</button> -->
                                                        <button class="btn btn-primary px-4 rounded-pill" type="submit">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="" class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-8">
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        
                    </div> -->
                </form>
            </div>
        </div>
    </div>
  </body>
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
</html>
