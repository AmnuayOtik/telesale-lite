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
                <div class="col-12" id="NewFormModal"><!-- Modal Open here --></div>
            </div>

            <div class="row">
                <!-- Modal Change Password -->
                <div class="modal fade" id="changePasswordModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        
                        <div class="modal-header" style="border-color: bisque;background-color: antiquewhite;">
                            <h4 class="modal-title">เปลี่ยนรหัสผ่าน : <span id="result_user"></span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
                        </div>
                        
                        <div class="modal-body">
                            <form id="changePasswordForm">          
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">รหัสผ่าน</label>
                                <input type="hidden" name="user_id" id="user_id">
                                <input type="password" class="form-control" id="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                            </form>
                        </div>
                        
                        <div class="modal-footer justify-content-between">                            
                            <button type="button" class="btn btn-default" data-dismiss="modal"> <span class="fas fa-times-circle"></span> ปิด</button>
                            <button type="button" class="btn btn-success" id="changePasswordForm" onclick="FcSavePasswordChange();"><span class="fas fa-save"></span> บันทึก</button>
                        </div>
                        
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายชื่อลูกค้า</h3>
                            <div class="card-tools">                                
                                <button type="button" class="btn btn-primary btn-sm shadow-btn" style="border-radius: 25px 25px 25px 25px;" onclick="FcShowUsersModalByUID('new','');"><span class="fas fa-plus"></span> เพิ่มข้อมูล</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;" id="UsersTableBody" >
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
        FcReloadUsersTable();
    });

    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/ 
    function FcChangeUsersPassWdByUID(current_user_id = '') {
        // เคลียร์ค่าทุกช่อง input ในฟอร์ม
        $('#changePasswordForm')[0].reset();

        // ถ้ามีข้อความแจ้งเตือนหรือ error message ต่างๆ ให้เคลียร์ด้วย
        $('#passwordError').text(''); // สมมุติว่าคุณมี id นี้ไว้แสดง error

        // เซ็ตค่าที่ต้องการ
        $('#result_user').text(current_user_id);
        $('#user_id').val(current_user_id);

        // แสดง Modal
        $('#changePasswordModal').modal('show');
    }


    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/ 
    function FcSavePasswordChange(){

        const user_id = $('#user_id').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();

        if(!user_id || !newPassword || !confirmPassword){
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน <br> Please fill in all required fields.');
            return;
        }
        
        if(newPassword != confirmPassword){
            toastr.error('ยืนยันรหัสผ่านไม่ถูกต้อง ลองใหม่อีกครั้ง <br> Password not match.');
            return;
        }

        $.ajax({
            url: '<?=base_url('Users/FcSavePasswordChange');?>',
            type: 'POST',
            data: { user_id: user_id, newPassword: newPassword },             
            success: function(response) {                
                if(response.rCode == 200 && response.rMsg == 'Success'){
                    $('#changePasswordModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'เปลี่ยนรหัสผ่านสำเร็จ',
                        text: 'รหัสผ่านของคุณถูกเปลี่ยนเรียบร้อยแล้ว',
                        timer: 2000,
                        showConfirmButton: false
                    });                    
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเปลี่ยนรหัสผ่านได้',
                    });
                }                
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });

    }

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มใหม่
    *********************************************************/  
    function FcShowUsersModalByUID(mode = 'readonly', user_id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Users/FcFetchUsersModal');?>',
            type: 'POST',
            data: { mode: mode, user_id: user_id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#UsersModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
            },
            error: function(xhr, status, error) {                            
                console.error('Error:', error);                         
            }
        });
    }

    /*********************************************************
     * ฟังก์ชั่นลบรายการออก (แสดง SweetAlert ก่อนลบ)
     *********************************************************/  
    function FcDelUsersByUID(user_id = null) {

        if (!user_id) {
            toastr.error('ไม่พบรหัสลูกค้า');
            return;
        }

        // แสดง SweetAlert เพื่อให้ผู้ใช้ยืนยันก่อนลบ
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบผู้ใช้นี้จริงหรือไม่?",
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
                    url: '<?=base_url('Users/FcDelUsersByUID');?>',
                    type: 'POST',
                    data: { user_id: user_id },
                    dataType: 'json', // ให้ jQuery แปลง response เป็น JSON อัตโนมัติ
                    success: function(response) {  
                        if (response.rCode === 200 && response.rMsg === 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: 'ข้อมูลผู้ใช้ถูกลบแล้ว',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // โหลดตารางข้อมูลใหม่หลังจากลบ                            
                            FcReloadUsersTable();
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถลบผู้ใช้ได้',
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
    * ฟังก์ชั่นเปิดฟอร์มเพื่อแก้ไขรายการ
    *********************************************************/  
    function FcEditUsersModalByUID(mode = 'readonly', user_id = null){       
        $('#NewFormModal').html();
        $.ajax({
            url: '<?=base_url('Users/FcFetchUsersModal');?>',
            type: 'POST',
            data: { mode: mode, user_id: user_id },             
            success: function(response) {                
                $('#NewFormModal').html(response);
                $('#UsersModal').modal({ backdrop: 'static', keyboard: false }).modal('show'); // Prevent closing when clicking outside
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
    function FcReloadUsersTable() {
        $.get('Users/FcUsersTables', function(response) {
            $('#UsersTableBody').html(response);
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
                            FcReloadUsersTable();
                            
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
                            FcReloadUsersTable();
                            
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


</script>