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
                <div id="ChangePasswordModal"><!-- open modal seach here --></div>
            </div>

            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายละเอียดผู้ใช้งาน</h3>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;" >

                            <form method="POST" name="frmEditUser" id="frmEditUser">

                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="user_id">รหัสประจำตัว (user id)</label>
                                            <input type="text" class="form-control" id="user_id" name="user_id" maxlength="20" value="<?=$user['user_id']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="username">ชื่อผู้ใช้ (username)</label>
                                            <input type="text" class="form-control" id="username" name="username" maxlength="50" value="<?=$user['username'];?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="business_phone">PBX Extension</label>
                                            <input type="text" class="form-control" id="business_phone" name="business_phone" maxlength="7" value="<?=$user['business_phone'];?>" style="border-color: red;">
                                        </div>
                                    </div>

                                </div>     
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="full_name">ชื่อ - นามสกุล (Full name)</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" maxlength="100" value="<?=$user['full_name'];?>">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="email">อีเมล์ (Email)</label>
                                            <input type="text" class="form-control" id="email" name="email" maxlength="100" value="<?=$user['email'];?>">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="mobile_phone">เบอร์โทรศัพท์ (Mobile Phone)</label>
                                            <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" maxlength="20" value="<?=$user['mobile_phone'];?>">
                                        </div>
                                    </div>

                                </div> 
                                <div class="row">
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-success" name="cmdSaveUser" id="cmdSaveUser"><span class="fas fa-save"></span> แก้ไขข้อมูล</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->


            </div>
            <!-- /.row (main row) -->

            <!-- Main row -->
            <div class="row">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">เปลี่ยนรหัสผ่าน</h3>
                        </div>
                        <div class="card-body table-responsive" style="padding: 20px !important;">

                            <form method="post" name="frmChangePassword" id="frmChangePassword">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="current_password">รหัสผ่านเดิม (Current Password)</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" maxlength="50" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="new_password">รหัสผ่านใหม่ (New Password)</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" maxlength="50" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- ref_user_id -->
                                        <div class="form-group">
                                            <label for="confirm_password">ยืนยันรหัสผ่านใหม่ (Confirm Password)</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" maxlength="50" value="">
                                        </div>
                                    </div>

                                </div>     
                                
                                <div class="row">
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-success" name="cmdSavePassword" id="cmdSavePassword"><span class="fas fa-save"></span> แก้ไขรหัสผ่าน</button>
                                    </div>
                                </div>

                            </form>

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

    /*********************************************************
    * ฟังก์ชั่นแก้ไขข้อมูลผู้ใช้งาน
    *********************************************************/ 
    $('#cmdSaveUser').click(function () {
        const username = $('#username').val();
        const business_phone = $('#business_phone').val();
        const full_name = $('#full_name').val();
        const formData = $('#frmEditUser').serialize();

        if (username === '' || full_name === '') {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน', 'เกิดข้อผิดพลาด');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'UserInfo/FcSaveUser',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.rCode === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        showConfirmButton: false,
                        timer: 2000 // 2 วินาที แล้วปิดอัตโนมัติ
                    }).then(() => {
                        // ตัวอย่าง: รีเฟรชหน้าหรือไปหน้าถัดไป
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.rMsg || 'ไม่สามารถบันทึกข้อมูลได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
                toastr.error('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'เกิดข้อผิดพลาด');
            }
        });
    });

    /*********************************************************
    * ฟังก์ชั่นแก้ไขรหัสผ่านผู้ใช้งาน
    *********************************************************/ 
    $('#cmdSavePassword').click(function () {
        const current_password = $('#current_password').val().trim();
        const new_password = $('#new_password').val().trim();
        const confirm_password = $('#confirm_password').val().trim();

        if (current_password === '' || new_password === '' || confirm_password === '') {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน', 'เกิดข้อผิดพลาด');
            return;
        }

        if (new_password !== confirm_password) {
            toastr.error('รหัสผ่านใหม่ไม่ตรงกัน', 'เกิดข้อผิดพลาด');
            return;
        }

        const formData = {
            current_password: current_password,
            new_password: new_password,
            confirm_password: confirm_password
        };

        $.ajax({
            type: 'POST',
            url: 'UserInfo/FcChangePassword',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.rCode === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: response.rMsg || 'เปลี่ยนรหัสผ่านสำเร็จ',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        $('#current_password').val('');
                        $('#new_password').val('');
                        $('#confirm_password').val('');
                    });
                } else {
                    toastr.error(response.rMsg || 'เกิดข้อผิดพลาด', 'เกิดข้อผิดพลาด');
                }
            },
            error: function (xhr, status, error) {
                toastr.error('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'เกิดข้อผิดพลาด');
                console.log(error);
            }
        });
    });

</script>

