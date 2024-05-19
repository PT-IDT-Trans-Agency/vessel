<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Claims</title>
    <link href="<?= base_url("css/style.css") ?>" rel="stylesheet">
    <link href="<?= base_url("vendors/datatables.net-bs4/css/dataTables.bootstrap4.css") ?>" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript">var appcode = "<?= $appcode_local_storage ?>"</script>
    <script type="text/javascript">
        $(document).ready(function(){
            
        })
    </script>
</head>
<body class="c-app">
    <?php
        $this->load->view("templates/sidebar-admin.php");
    ?>
    <div class="c-wrapper c-fixed-components">
        <?php
            $this->load->view("templates/topbar-admin.php");
        ?>
        <div class="c-subheader px-3">
          <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="<?= base_url("dashboard") ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div >
                      <h4 class="card-title mb-0">USERS</h4>
                      <div class="small text-muted"><?=date("F Y") ?></div>
                    </div>
                  </div>
                  <div class="c-chart-wrapper" style="margin-top:30px;">
                    
                  </div>
                </div>
              </div>
              <?php $this->load->view("templates/modal-logout") ?>
              <!-- /.card-->
              <!-- /.row-->
            </div>
          </div>
        </main>
        <?php
            $this->load->view("templates/footer-admin");
            $this->load->view("templates/modal-spinner-please-wait");
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
  </body>

</html>