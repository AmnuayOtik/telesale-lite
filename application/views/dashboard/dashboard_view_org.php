<?php
  defined('BASEPATH') OR exit('No direct script access allowed');  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TeleSale System | MainPage</title>
    <link rel="stylesheet" href="<?=base_url('assets/plugins/fontawesome-free/css/all.min.css');?>">  
    <link rel="stylesheet" href="<?=base_url('assets/dist/css/adminlte.min.css');?>">
    <link rel="shortcut icon" href="<?=base_url('assets/images/favicon.ico');?>" />
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">
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

            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('Search');?>" role="button">
                <i class="fas fa-search"></i>
                </a>
            </li>

            <li class="nav-item">
                
                <a class="nav-link" href="#" role="button">Thai</a>
                
            </li>

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
            <span class="brand-text font-weight-light">TeleSale System</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                <img src="<?=base_url('assets/dist/img/user2-160x160.jpg');?>" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                <a href="#" class="d-block">xx</a>
                </div>
            </div>
        
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
                </div>
            </div>

        <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            หน้าหลัก
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>

                        <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=base_url('Dashboard');?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>แดชบอร์ด</p>
                            </a>
                        </li>                            
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="<?=base_url('Search');?>" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            ขายประจำวัน
                            <span class="right badge badge-danger">SaleTel</span>
                        </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?=base_url('Search');?>" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            ลูกค้า
                            <span class="right badge badge-danger">Contact</span>
                        </p>
                        </a>
                    </li>

                    <li class="nav-item">
                    <a href="<?=base_url('Search');?>" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                        บริษัท
                        <span class="right badge badge-danger">Company</span>
                        </p>
                    </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo base_url('Basic');?>" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                            แคมเพนจ์
                            <span class="right badge badge-danger">Campiagn</span>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?=base_url('Reports');?>" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            รายงาน
                            <span class="right badge badge-danger">Reports</span>
                        </p>
                        </a>
                    </li>

                    <li class="nav-header">กำหนดค่า</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                บริหารจัดการ
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">6</span>
                            </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?=base_url('Users');?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>จัดการผู้ใช้</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?=base_url('UserLevel');?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>กลุ่มผู้ใช้</p>
                                    </a>
                                </li>                
                            </ul>
                        </li>
                    </li>

                    <li class="nav-header">การใช้งาน</li>
                        <li class="nav-item">
                            <a href="<?=base_url('Logout');?>" class="nav-link">
                            <i class="nav-icon far fa-circle text-danger"></i>
                            <p class="text">ออกจากระบบ</p>
                            </a>
                        </li>
                    </li>

                </ul>
            </nav>
        <!-- /.sidebar-menu -->

        </div>
        <!-- /.sidebar -->
    </aside>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content-here">
    <!-- your content heer -->  

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>33333</h3>

                        <p>จำนวนรายการทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" onclick="SearchBy('1');" class="small-box-footer">แสดงรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3>3333333</h3>

                        <p>จำนวนรายการที่บันทึกโดยผู้ใช้</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" onclick="SearchBy('2');" class="small-box-footer">แสดงรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>333333</h3>

                        <p>จำนวนรายการที่บันทึกโดยผู้ใช้ เดือนปัจจุบัน</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" onclick="SearchBy('3');" class="small-box-footer">แสดงรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo date("Y-m-d H:i:s"); ?></h3>

                        <p>เข้าใช้งานครั้งล่าสุด</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer"> <i class="fas fa-clock"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">
                                
                            
                                
                                </h3>
                                <div class="card-tools">
                                <a href="<?=base_url('BasicExport');?>" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-bars"></i>
                                </a>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>Site ID</th>
                                    <th>Site Name (Thai)</th>
                                    <th>Site Name (Eng)</th>
                                    <th>Number</th>
                                                            
                                    <th>Updated By</th>
                                    <th>Last Update</th>
                                    <th>More</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td style="width:10%;">xx</td>
                                    <td>
                                        <img src="<?=base_url('assets/dist/img/default-150x150.png');?>" alt="Product 1" class="img-circle img-size-32 mr-2">
                                        xxx
                                    </td>
                                    <td>
                                    <img src="<?=base_url('assets/dist/img/default-150x150.png');?>" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    xxx
                                    </td>
                                    <td>xx</td>
                                    
                                    <td>xxx</td>
                                    <td>
                                    <small class="text-success mr-1">
                                        <i class="fas fa-arrow-down"></i>
                                        
                                    </small>
                                    xxx
                                    </td>
                                    <td>
                                    <a href="#" class="text-muted">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    </td>
                                </tr>
                                
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->


                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 0.1.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://www.otiknetwork.com">บริษัท โอติก เน็ตเวิร์ค จำกัด</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?=base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<!-- bs-custom-file-input -->
<script src="<?=base_url('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('assets/dist/js/adminlte.min.js');?>"></script>

</body>
</html>
