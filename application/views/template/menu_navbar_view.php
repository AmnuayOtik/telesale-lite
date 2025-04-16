<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=base_url('Dashboard');?>" class="nav-link">หน้าหลัก</a>
        </li>      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=base_url('Dashboard');?>" class="brand-link">
        <img src="<?=base_url('assets/dist/img/AdminLTELogo.png');?>" alt="Inventory Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">TeleSale-Lite</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="<?=base_url('assets/dist/img/user2-160x160.jpg');?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="#" class="d-block"><?=$this->session->userdata('full_name');?></a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" disabled>
            <div class="input-group-append">
                <button class="btn btn-sidebar" disabled>
                <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>

    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                 <!--
                <li class="nav-item <?= $this->session->userdata('menu_active') === 'dashboard' ? 'menu-is-opening menu-open' : '' ?>">
                    <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        หน้าหลัก
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?=base_url('Dashboard');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'dashboard' ? 'active' : '' ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>แดชบอร์ด</p>
                        </a>
                    </li>                            
                    </ul>
                </li>     
                -->          

                <li class="nav-item">
                    <a href="<?=base_url('Customer');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'customer' ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-th"></i>
                        <p>
                            รายการโทร
                            <span class="right badge badge-danger">Telesale</span>
                        </p>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a href="<?=base_url('Product');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'product' ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-plus"></i>
                        <p>
                            สินค้า
                            <span class="right badge badge-danger">Product</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                <a href="<?=base_url('Company');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'company' ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                    บริษัท
                    <span class="right badge badge-danger">Company</span>
                    </p>
                </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo base_url('Campaign');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'campaign' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                        แคมเพนจ์
                        <span class="right badge badge-danger">Campiagn</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?=base_url('Reports');?>" class="nav-link <?= $this->session->userdata('menu_active') === 'report' ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        รายงาน
                        <span class="right badge badge-danger">Reports</span>
                    </p>
                    </a>
                </li>
            -->

                <?php if($this->session->userdata('is_admin')==true){?>
                <li class="nav-header">กำหนดค่า</li>
                    <li class="nav-item">
                        <a href="Setting" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            บริหารจัดการ
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">Users</span>
                        </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?=base_url('Users');?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>จัดการผู้ใช้</p>
                                </a>
                            </li>
                                         
                        </ul>
                    </li>
                </li>
                <?php } ?>
                

                <li class="nav-header">การใช้งาน</li>
                <li class="nav-item">
                    <a href="<?=base_url('UserInfo');?>" id="users_info" class="nav-link">
                        <i class="nav-icon far fa-user text-warning"></i>
                        <p class="text">ข้อมูลผู้ใช้</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" id="logout-btn" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text">ออกจากระบบ</p>
                    </a>
                </li>

            </ul>
        </nav>
    <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>

<script>
$(document).ready(function() {
    $('#logout-btn').on('click', function(e) {
        e.preventDefault(); // ป้องกันการ redirect ทันที

        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการออกจากระบบ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ออกจากระบบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?=base_url('Login/FcLogout');?>";
            }
        });
    });
});
</script>
