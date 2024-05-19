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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
   
    <title>Dashboard 1</title>
    <script type="text/javascript">
        $(document).ready(function(){
            Load()

            $("#cabang_ringkasan_vlu").change(function(){
                GetDataViewVlu()
            })
            $("#tahun_ringkasan_vlu").change(function(){
                GetDataViewVlu()
            })
        })

        function Load(){
            $.ajax({
                method :"get",
                url : "<?= base_url("apidashboard/get_data_filter_vlu") ?>",
                dataType:"json",
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    $("#cabang_ringkasan_vlu").html(createHtmlComboBranch(res.data_branch));
                    $("#tahun_ringkasan_vlu").html(createHtmlFilterTahun(res.data_filter_tahun))
                },
                error : function(xhr, status, error){
                    // var errorMessage = xhr.status + " : " + xhr.statusText;
                    // alert("Error - " + errorMessage)
                }
            }).done(function(){
                GetDataViewVlu()
            })
        }

        function GetDataViewVlu(){
            $.ajax({
                method :"get",
                url : "<?= base_url("apidashboard/gets_summary_perbulan_vessel") ?>",
                dataType:"json",
                data : {
                    branch_code : $("#cabang_ringkasan_vlu").val(),
                    tahun : $("#tahun_ringkasan_vlu").val()
                },
                async : false,
                headers: {
                    "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    var namaBulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                    var data_perbulan = [];
                    for (let i = 0; i < 12 ; i++) {
                        let _bulan = res.data.filter(b => b.bulan == i + 1);
                        if (_bulan.length == 0){
                            data = {
                                bulan : i + 1,
                                nama_bulan : namaBulan[i],
                                total_vessel : 0
                            }    
                        } else {
                            data = {
                                bulan : i + 1,
                                nama_bulan : namaBulan[i],
                                total_vessel : _bulan[0].total_vessel
                            }    
                        }
                        data_perbulan.push(data);
                    }
                    SetDataViewRingkasanVlu(data_perbulan)
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChartVlu);
                    setTimeout(() => {
                        drawChartVlu(data_perbulan)
                    }, 500);
                },
                error : function(xhr, status, error){
                    // var errorMessage = xhr.status + " : " + xhr.statusText;
                    // alert("Error - " + errorMessage)
                },
                beforeSend : function(){
                    // ClearTablePerMonth()
                }
            }).done(function(){
            })
        }

        function createHtmlComboBranch(data){
            var html = "<option value=''>Semua Cabang</option>"
            data.forEach(el => {
                html += "<option value='"+el.branch_code+"'>"+el.branch_name+"</option>"
            });
            return html;
        }

        function createHtmlFilterTahun(data){
            nowYear = new Date().getFullYear();
            var html = ""
            data.forEach(el => {
                html += "<option value='"+el.tahun+"' "+(nowYear == el.tahun ? "selected" : "")+">"+el.tahun+"</option>"
            });
         
            return html;
        }

        function SetDataViewRingkasanVlu(data){
            table.destroy();
            table = $("#ringkasan-vlu").DataTable({
                data : data,
                paging: false,
                scrollX: true,
                scrollCollapse: true,
                autoWidth : true,
                scrollCollapse: true,
                fixedHeader: true,
                autoWidth: true,
                ordering : false,
                searching : false,
                columnDefs: [
                    // { "width": "100px", "targets": [0] , className : "text-left"},
                    { "width": "150px", "targets": [1] , className : "text-left"},
                ],
                "columns" : [
                    {data :  null,
                        render : (data) =>{
                            return data.nama_bulan
                        }
                    },
                    {data :  null,
                        render : (data) =>{
                            return data.total_vessel
                        }
                    },
                ]
            })
        }

        function drawChartVlu(datasVlu) {
            var service_value_idr = [];
            var service_value_usd = [];
            for (let i = 1; i <= 12; i++) {
                service_value_idr[i] = 0;
                service_value_usd[i] = 0;
                datasFHE.forEach(el => {
                    if (parseFloat(el.bulan) == i  ){
                        service_value_idr[i] = el.sum_service_value_idr;
                        service_value_usd[i] = el.sum_service_value_usd;
                    }
                });
            }
            
            data_perbulan = [['Month', 'Total Vessel']];
            datasVlu.forEach(el => {
                data.push([
                    el.nama_bulan,
                    el.total_vessel
                ])
            });

            console.log(data_perbulan);
            // var data = google.visualization.arrayToDataTable([
            //     ['Month', 'Total Vessel'],
            //     ['Jan',  service_value_usd[1], service_value_idr[1]],
            //     ['Feb',  service_value_usd[2],      service_value_idr[2]],
            //     ['Mar',  service_value_usd[3],       service_value_idr[3]],
            //     ['Apr',  service_value_usd[4],      service_value_idr[4]],
            //     ['Mei',  service_value_usd[5],      service_value_idr[5]],
            //     ['Jun',  service_value_usd[6],      service_value_idr[6]],
            //     ['Jul',  service_value_usd[7],      service_value_idr[7]],
            //     ['Agu',  service_value_usd[8],      service_value_idr[8]],
            //     ['Sep',  service_value_usd[9],      service_value_idr[9]],
            //     ['Okt',  service_value_usd[10],      service_value_idr[10]],
            //     ['Nov',  service_value_usd[11],      service_value_idr[11]],
            //     ['Des',  service_value_usd[12],      service_value_idr[12]],
            // ]);
            var data = google.visualization.arrayToDataTable(data_perbulan);

            // var options = {
            //     title: 'Company Performance',
            //     subtitle: 'Final Handling Expenses Profit : '+$("#cabang_ringkasan_nilai_fhe :selected").text()+' - '+$("#tahun_ringkasan_nilai_fhe :selected").text(),
            // };
            var chart = new google.charts.Bar(document.getElementById('chart_div_vlu'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
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
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      <div class="c-body">
        <main class="c-main">
          <div class="container-fluid">
            <div class="fade-in">
            <div class="row">
                    <div class="col-sm-12">
                        <h4 class="card-title mb-0 text-muted">Vessel Line Up</h4>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body card-accent-primary">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-0 text-primary">Ringkasan</h4>
                                        <small><?= date("M, d Y") ?></small>
                                    </div>
                                    <div>
                                        <div class="d-flex">
                                            <div class="flex-fill px-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text text-primary">
                                                                Cabang
                                                            </span>
                                                        </div>
                                                        <select name="cabang_ringkasan_vlu" id="cabang_ringkasan_vlu" class="form-control col-sm-10">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text text-primary">
                                                                Tahun
                                                            </span>
                                                        </div>
                                                        <select name="tahun_ringkasan_vlu" id="tahun_ringkasan_vlu" class="form-control col-sm-10">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="c-chart-wrapper">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-striped" id="ringkasan-vlu" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="bg-primary text-white" style="border-radius:10px 0px 0px 0px">Bulan</th>
                                                        <th class="bg-primary text-white" style="border-radius:0px 10px 0px 0px;text-align:right">Total Vessel</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <div id="chart_div_vlu" style="width: 100%; height: 500px;"></div>
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
        var table = $("#ringkasan-vlu").DataTable({
            "ordering": false,
            "searching" : false,
            "lengthChange" : false,
            pageLength: 6,
            columnDefs: [
                // { "width": "100px", "targets": [0] , className : "text-left"},
                { "width": "150px", "targets": [1] , className : "text-left"},
            ],
        });
    </script>
    
    
  </body>
</html>