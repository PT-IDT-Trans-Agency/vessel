<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
          <svg class="c-icon c-icon-lg">
            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-menu"></use>
          </svg>
        </button><a class="c-header-brand d-lg-none" href="#">
          <svg width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="assets/brand/coreui.svg#full"></use>
          </svg></a>
        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <i class="fas fa-list"></i>
        </button>
        <ul class="c-header-nav ml-auto mr-4">
          <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#">
            <?= $this->session->user_kode_cabang." - ".$this->session->user_nama_cabang; ?>
          </li>
          <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <div class="c-avatar"><img class="c-avatar-img" src="<?= base_url("assets/img/avatars/user-icon.png") ?>" alt="user@email.com"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
              <div class="dropdown-header bg-light py-2">
                <strong>Logged in : <?= $this->session->user_name ?></strong>
              </div>
              <a class="dropdown-item" href="#"  data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> &nbsp;&nbsp;Logout
              </a>
            </div>
          </li>
        </ul>
        
      </header>