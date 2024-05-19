<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
      <div class="c-sidebar-brand d-lg-down-none">
        <!-- <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
          <use xlink:href="<?= base_url("assets/brand/coreui.svg#full") ?>"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
          <use xlink:href="<?= base_url("assets/brand/coreui.svg#signet") ?>"></use>
        </svg> -->
        <img src="<?= base_url("docs/img/logo.png") ?>" alt="" width="100" height="46" class="c-sidebar-brand-full">
        <img src="<?= base_url("docs/img/logo.png") ?>" alt="" width="46" height="46" class="c-sidebar-brand-minimized">
        <!-- IDT Workjob -->
      </div>
      <ul class="c-sidebar-nav">
          <?php
            if ($this->session->qry_Dashboard->list == 1){
              ?>
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown"><a class="c-sidebar-nav-link" href="<?= base_url("dashboard") ?>">
                    <span class="c-sidebar-nav-icon fas fa-columns">
                    </span>Dashboard</a>
                </li>
              <?php
            }
          ?>
          <?php
            if ($this->session->qry_VesselLineUp->list == 1 || $this->session->qry_VesselLineUp->create == 1){
              ?>
                <li class="c-sidebar-nav-title">Menu Utama</li>
                  <li class="c-sidebar-nav-item">
                    <ul class="c-sidebar-nav">
                      <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                          <span class="c-sidebar-nav-icon fas fa-solid fa-file-invoice">
                          </span>Vessel Line Up
                        </a>
                        <ul class="c-sidebar-nav-dropdown-items">
                          <?php
                            if ($this->session->qry_VesselLineUp->create == 1){
                              ?>
                                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("vessel-line-up/add") ?>">
                                    <span class="c-sidebar-nav-icon far fa-circle">
                                    </span>Isi Data</a>
                                </li>
                              <?php
                            }
                          ?>
                          <?php
                            if ($this->session->qry_VesselLineUp->list == 1){
                              ?>
                                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("vessel-line-up") ?>">
                                    <span class="c-sidebar-nav-icon far fa-circle">
                                    </span>Lihat Daftar</a>
                                </li>
                              <?php
                            }
                          ?>
                        </ul>
                      </li>
                    </ul>
                  </li>
              <?php
            }
          ?>
          <?php
            if ($this->session->qry_MasterAgent->list == 1 || $this->session->qry_MasterBuyer->list == 1
                || $this->session->qry_MasterDestination->list == 1 || $this->session->qry_MasterPBM->list == 1 || $this->session->qry_MasterPort->list == 1
                || $this->session->qry_MasterShipper->list == 1 || $this->session->qry_MasterVessel->list == 1){
              ?>
                <li class="c-sidebar-nav-title">Konfigurasi</li>
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown"><a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                    <span class="c-sidebar-nav-icon fas fa-box">
                    </span>Master</a>
                    <ul class="c-sidebar-nav-dropdown-items">
                      <?php
                        if ($this->session->qry_MasterAgent->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("agent") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Agent</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterBuyer->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("buyer") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Buyer</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterDestination->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("destination") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Destination</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterPBM->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("pbm") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Perusahaan Bongkar Muat</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterPort->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("port") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Port</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterShipper->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("shipper") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Shipper</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterVessel->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("vessel") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Vessel</a></li>
                          <?php
                        }
                      ?>
                    </ul>
                </li>
              <?php
            }
          ?>

          <?php
            if ($this->session->qry_MasterUser->list == 1 || $this->session->qry_MasterGroup->list == 1 || $this->session->qry_MasterCabang->list == 1){
              ?>
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown"><a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                    <span class="c-sidebar-nav-icon fas fa-cog">
                    </span>Sistem</a>
                    <ul class="c-sidebar-nav-dropdown-items">
                      <?php
                        if ($this->session->qry_MasterUser->list == 1 || $this->session->qry_MasterGroup->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("user") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> User Role & Menu Setting</a></li>
                          <?php
                        }
                      ?>
                      <?php
                        if ($this->session->qry_MasterCabang->list == 1){
                          ?>
                            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="<?= base_url("cabang") ?>"><span class="c-sidebar-nav-icon far fa-circle"></span> Cabang</a></li>
                          <?php
                        }
                      ?>
                      
                    </ul>
                </li>
              <?php
            }
          ?>
      </ul>
      <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
    </div>