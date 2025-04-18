<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<style>
    .custom-item {
        transition: background-color 0.3s, transform 0.2s;
    }

    .custom-item:hover {
        background-color: #e6f7ff !important;
        transform: scale(1.02);
    }

    .custom-link {
        transition: color 0.3s;
    }

    .custom-item:hover .custom-link {
        color: #0056b3 !important;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="content-here">    

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?=$header_content['title'];?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=base_url('Dashboard');?>">หน้าหลัก</a></li>
              <li class="breadcrumb-item active"><?=$header_content['right_menu'];?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12" id="NewFormModal"><!-- Modal Open here --></div>
            </div>

            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายงานทั้งหมดในระบบ</h3>
                            <div class="card-tools">
                            <button class="btn btn-default btn-sm" id="toggle-btn" style="border-radius: 30px;" type="button">
                                <span class="fas fa-chevron-down" id="toggle-icon"></span>
                            </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;" id="ReportsTableBody" >
                            
                            <ol class="list-group list-group-numbered" style="padding-left: 0;">
                                <li class="list-group-item custom-item" style="border: none; background-color: #f0f8ff; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                    <a href="#" class="custom-link" style="text-decoration: none; color: #007bff; font-weight: bold;" onclick="FcOpenReport('1');">📝 1. รายงานสรุปยอดการโทร - ตามวันที่กำหนด</a>
                                </li>
                                <li class="list-group-item custom-item" style="border: none; background-color: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                    <a href="#" class="custom-link" style="text-decoration: none; color: #007bff; font-weight: bold;" onclick="FcOpenReport('2');">📄 2. รายงานสินค้าเข้าออก</a>
                                </li>
                                <li class="list-group-item custom-item" style="border: none; background-color: #f0f8ff; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                    <a href="#" class="custom-link" style="text-decoration: none; color: #007bff; font-weight: bold;" onclick="FcOpenReport('3');">📊 3. รายงานเปรียบเทียบรายเดือน</a>
                                </li>
                            </ol>

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

<script>
    
    $(document).ready(function(){

        // แสดงข้อมูลลิขสิทธิ์ใน Console
        console.log("-----------------------------------------");
        console.log('ห้ามนำไปเผยแพร่โดยไม่ได้รับอนุญาต                ');        
        console.log("-----------------------------------------");

    });

    function FcOpenReport(report_id = ''){

        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Reports/FcOpenReportModal');?>',
            type: 'POST',
            data: { report_id: report_id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#ReportsformModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        }); 

    }

    $('#toggle-btn').click(function () {
        $('#ReportsTableBody').slideToggle(); // ซ่อน/แสดงด้วย animation
        $('#toggle-icon').toggleClass('fa-chevron-down fa-chevron-up'); // สลับไอคอน
    });

</script>




</script>