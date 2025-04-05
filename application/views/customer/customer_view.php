<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

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
            <!-- Small boxes (Stat box) -->
            <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                <div class="inner">
                    <h3><span id="rWaiting">0</span>/<small style="font-size:23px;color:yellow;" id="rTotal">0</small></h3>

                    <p>จำนวนรายการที่โทรแล้ว/ทั้งหมด</p>
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
                    <h3><span id="rFinished">0</span></h3>

                    <p>จำนวนรายการที่ปิดได้</p>
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
                    <h3><span id="rPostpone">0</span></h3>

                    <p>จำนวนรายการที่ถูกเลื่อน</p>
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
                    <h3><span id="rIncomplete">0</span></h3>

                    <p>จำนวนรายการที่ไม่สำเร็จ</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">แสดงรายละเอียด <i class="fas fa-clock"></i></a>
                </div>
            </div>
            <!-- ./col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-12" id="NewFormModal"><!-- Modal Open here --></div>
            </div>
            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายชื่อลูกค้า</h3>
                            <div class="card-tools">                            
                            <button type="button" class="btn btn-warning btn-sm shadow-btn" style="border-radius: 25px 25px 25px 25px;" onclick="FcBulkImportCustomer();"><span class="fas fa-cloud-download-alt"></span> นำเข้าข้อมูล</button>
                                <button type="button" class="btn btn-danger btn-sm shadow-btn" style="border-radius: 25px 25px 25px 25px;" onclick="FcDelBulkCustomerByCID();"><span class="fas fa-plus"></span> ลบรายการ</button>
                                <button type="button" class="btn btn-primary btn-sm shadow-btn" style="border-radius: 25px 25px 25px 25px;" onclick="fcShowCustomerModalByCID('new','');"><span class="fas fa-plus"></span> เพิ่มข้อมูล</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;" id="CustomerTableBody" >
                            <!-- ตัวอย่างข้อมูลลูกค้า -->                            
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
        console.log("-----------------------------------------")
        console.log('พัฒนาโดย บริษัท โอติกเน็ตเวิร์ค จำกัด');
        console.log('ห้ามนำไปเผยแพร่โดยไม่ได้รับอนุญาต');
        console.log('ติดต่อ www.otiknetwork.com | 02-538-4378');
        console.log("-----------------------------------------")
        FcGetTodayStatByUserID();
        fcReloadCustomerTable();
    });


    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มใหม่
    *********************************************************/  
    function fcShowCustomerModalByCID(mode = 'readonly', customer_id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Customer/FcFetchCustomerModal');?>',
            type: 'POST',
            data: { mode: mode, customer_id: customer_id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#CustomerModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });
    }

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มเพื่อแก้ไขรายการ
    *********************************************************/  
    function FcEditCustomerModalByCID(mode = 'readonly', customer_id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Customer/FcFetchCustomerModal');?>',
            type: 'POST',
            data: { mode: mode, customer_id: customer_id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#CustomerModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });
    }

    /*********************************************************
    * ฟังก์ชั่นแสดง FollowUps ของลูกค้า
    *********************************************************/ 
    function FcFollowUpsByCID(customer_id = null) {
        if (!customer_id) {
            toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            return false;
        } else {
            $.post('Customer/FcCustomerTables', { customer_id: customer_id }, function(response) {
                // Redirect ไปยัง URL ใหม่โดยแทนที่หน้าเดิม
                window.location.replace("Followups");
            }).fail(function() {
                toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            });
        }
    }


    /*********************************************************
    * ฟังก์ชั่นแสดงตารางลูกค้า
    *********************************************************/ 
    function fcReloadCustomerTable() {
        $.get('Customer/FcCustomerTables', function(response) {
            $('#CustomerTableBody').html(response);
        }).fail(function() {
            toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    }

    /*********************************************************
     * ฟังก์ชั่นลบรายการออก (แสดง SweetAlert ก่อนลบ)
     *********************************************************/  
    function FcDelCustomerByCID(customer_id = null) {

        if (!customer_id) {
            toastr.error('ไม่พบรหัสลูกค้า');
            return;
        }

        // แสดง SweetAlert เพื่อให้ผู้ใช้ยืนยันก่อนลบ
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบลูกค้านี้จริงหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้าผู้ใช้กดยืนยัน ให้ทำการส่ง AJAX
                $.ajax({
                    url: '<?=base_url('Customer/FcDelCustomer');?>',
                    type: 'POST',
                    data: { customer_id: customer_id },
                    dataType: 'json', // ให้ jQuery แปลง response เป็น JSON อัตโนมัติ
                    success: function(response) {  
                        if (response.rCode === 200 && response.rMsg === 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: 'ข้อมูลลูกค้าถูกลบแล้ว',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // โหลดตารางข้อมูลใหม่หลังจากลบ
                            fcReloadCustomerTable();
                            FcGetTodayStatByUserID();
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถลบลูกค้าได้',
                            });
                        }
                    },
                    error: function(xhr, status, error) {                            
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                        });
                    }
                });
            }
        });
    }

    /*********************************************************
    * ฟังก์ชั่นลบหลายๆ รายการออก (แสดง SweetAlert ก่อนลบ)
    *********************************************************/  
    function FcDelBulkCustomerByCID(){
        // ดึง customer_id ที่ถูกเลือกจาก checkbox
        var selectedCustomers = [];
        $('input[name="customer[]"]:checked').each(function() {
            selectedCustomers.push($(this).val());
        });

        // ตรวจสอบว่ามีการเลือกลูกค้าหรือไม่
        if (selectedCustomers.length === 0) {
            toastr.error('กรุณาเลือกข้อมูลที่ต้องการลบ.');
            return;
        }

        // แสดงการยืนยันจาก SweetAlert
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบข้อมูลลูกค้าเหล่านี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบข้อมูล!'
        }).then((result) => {
            if (result.isConfirmed) {
                // ส่งข้อมูลไปยังเซิร์ฟเวอร์เพื่อทำการลบ
                $.ajax({
                    url: '<?=base_url('Customer/FcDeleteSelectedCustomers');?>', // URL ที่จะใช้ในการลบ
                    type: 'POST',
                    data: {customer_ids: selectedCustomers}, // ส่ง customer_ids ที่ถูกเลือก
                    dataType: 'json',
                    success: function(response) {
                        if (response.rCode == 200 && response.rMsg == 'Success') {
                            toastr.success('ลบข้อมูลลูกค้าเรียบร้อยแล้ว!');
                            // รีเฟรชหน้า หรือทำการอัปเดตตาราง                            
                            fcReloadCustomerTable();
                            
                        } else {
                            toastr.error('ไม่สามารถลบข้อมูลลูกค้าได้.');
                        }
                    },
                    error: function() {
                        toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    }
                });
            }
        });
    }

    function FcBulkImportCustomer(){
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Customer/FcCsvImportCustomerModal');?>',
            type: 'GET',
            data: { },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#CustomerCsvImportModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });
    }

    function FcGetTodayStatByUserID(){
        
        $.ajax({
            url: '<?=base_url('Customer/FcGetTodayStatByUserID');?>',
            type: 'GET',
            data: { },             
            success: function(response) {         
                
                if(response.rCode == 200 && response.rMsg == 'Success'){

                    $('#rWaiting').text(response.rData[0].Waiting);
                    $('#rFinished').text(response.rData[0].Finished);
                    $('#rPostpone').text(response.rData[0].Postpone);
                    $('#rIncomplete').text(response.rData[0].Incomplete);
                    $('#rTotal').text(
                        parseInt(response.rData[0].Waiting) + 
                        parseInt(response.rData[0].Finished) + 
                        parseInt(response.rData[0].Postpone) + 
                        parseInt(response.rData[0].Incomplete)
                    );
                }  
                            
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });

    }



</script>