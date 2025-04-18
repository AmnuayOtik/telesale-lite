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
            
            <div class="row">
                <div class="col-12" id="NewFormModal"><!-- Modal Open here --></div>
            </div>

            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายการ</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm shadow-btn" style="border-radius: 25px 25px 25px 25px;" onclick="fcShowCallInformModalByID('new','');"><span class="fas fa-plus"></span> เพิ่มข้อมูล</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;" id="CallInformTableBody" >
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
        console.log("-----------------------------------------");
        console.log('ห้ามนำไปเผยแพร่โดยไม่ได้รับอนุญาต                ');        
        console.log("-----------------------------------------");

        fcReloadCallInformTable();

    });

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มใหม่
    *********************************************************/  
    function fcShowCallInformModalByID(mode = 'readonly', id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('CallInform/FcFetchCallInformModal');?>',
            type: 'POST',
            data: { mode: mode, id: id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#CallInformModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });        
    }

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มเพื่อแก้ไขรายการ
    *********************************************************/  
    function FcEditCallInformModalByID(mode = 'readonly', id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('CallInform/FcFetchCallInformModal');?>',
            type: 'POST',
            data: { mode: mode, id: id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#CallInformModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });
    }

    /*********************************************************
    * ฟังก์ชั่นแสดงตารางลูกค้า
    *********************************************************/ 
    function fcReloadCallInformTable() {
        $.get('CallInform/FcCallInformTables', function(response) {
            $('#CallInformTableBody').html(response);
        }).fail(function() {
            toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    }

    /*********************************************************
     * ฟังก์ชั่นลบรายการออก (แสดง SweetAlert ก่อนลบ)
     *********************************************************/  
    function FcDelCallInformByID(id = null) {

        if (!id) {
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
                    url: '<?=base_url('CallInform/FcDelCallInform');?>',
                    type: 'POST',
                    data: { id: id },
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
                            fcReloadCallInformTable();                            
                            
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

</script>