<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content-here">

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