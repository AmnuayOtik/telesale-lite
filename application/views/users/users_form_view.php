<?php

// ทำการตั้งค่า Default โหมด หากไม่มีการกำหนดเข้ามา
$Permission = isset($mode) && ($mode === 'new') ? '' : 'readonly';
$DisabledPermission = isset($mode) && ($mode === 'edit' || $mode === 'new') ? '' : 'disabled';

// เตรียมข้อมูลเพื่อแสดงรายการ
$x_user_id = isset($user['user_id']) ? $user['user_id'] : '';
$x_username = isset($user['username']) ? $user['username'] : '';
$x_full_name = isset($user['full_name']) ? $user['full_name'] : '';
$x_mobile_phone = isset($user['mobile_phone']) ? $user['mobile_phone'] : '';
$x_business_phone = isset($user['business_phone']) ? $user['business_phone'] : '';
$x_email = isset($user['email']) ? $user['email'] : '';
$x_user_type = isset($user['user_type']) ? $user['user_type'] : '';
$x_cstatus = isset($user['cstatus']) ? $user['cstatus'] : '';


?>

<div class="modal fade" id="UsersModal">
    <div class="modal-dialog modal-lg" style="height: 83vh;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: beige;">
                <h4 class="modal-title">ฟอร์มบันทึกผู้ใช้งาน | Users Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="false">&times;</span>
                </button>
            </div>
        <div class="modal-body" style="max-height:78vh; overflow-y: auto;">                            
            
                <div class="card-body" style="padding: 14px;">
                    
                    <div class="card card-primary card-outline card-outline-tabs">
                        
                        <div class="card-header p-0 border-bottom-0">                                    
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item" id="cmdOne">
                        
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false"><span class="fas fa-user"></span> เพิ่มรายการใหม่</a>
                                </li>                                       
                            </ul>
                        </div>
                        
                        <div class="card-body" id="FormCustomer">
                            
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

                                    <form name="frmUsers" action="Users/FcSaveOrEdit" method="post" id="frmUsers">

                                        <!-- Customer ID (Auto generated) -->
                                        <div class="form-group">
                                            <label for="user_id">รหัสพนักงาน</label>
                                            <input type="hidden" name="mode" id="mode" value="<?=$mode;?>">
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="user_id" name="user_id" maxlength="10" value="<?=$x_user_id?>" placeholder="Auto" readonly>
                                        </div>

                                        <!-- Full Name -->
                                        <div class="form-group">
                                            <label for="full_name">ชื่อ - นามสกุล</label>
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="full_name" name="full_name" maxlength="255" value="<?=$x_full_name;?>" placeholder="กรุณากรอกชื่อเต็ม">
                                        </div>

                                        <fieldset style="border: 1px solid red; padding: 15px; border-radius: 10px;margin-bottom: 13px;">
                                        <legend style="color: red; font-weight: bold;width: 308px;">รายละเอียดบัญชีการเข้าใช้งาน</legend>

                                            <!-- UserName -->
                                            <div class="form-group">
                                                <label for="username">ชื่อบัญชีใช้งาน</label>
                                                <input type="text" class="form-control" style="border-color: #f5b6b6;" id="username" name="username" maxlength="20" value="<?=$x_username;?>" placeholder="กรุณากรอกชื่อชัญชีที่ต้องการ" <?=$Permission;?>>
                                            </div>

                                            <!-- Password -->
                                            <div class="form-group">
                                                <label for="password">รหัสผ่าน</label>
                                                <input type="password" class="form-control" style="border-color: #f5b6b6;" id="password" name="password" maxlength="20" value="" placeholder="กรุณากรอกรหัสผ่านที่ต้องการ" <?=$Permission;?>>
                                            </div>

                                            <!-- Confirm Password -->
                                            <div class="form-group">
                                                <label for="confirmpassword">ยืนยันรหัสผ่าน</label>
                                                <input type="password" class="form-control" style="border-color: #f5b6b6;" id="confirmpassword" name="confirmpassword" maxlength="20" value="" placeholder="ยืนยันรหัสผ่านอีกครั้ง" <?=$Permission;?>>
                                            </div>
                                       
                                        </fieldset>

                                        <!-- Phone Number -->
                                        <div class="form-group">
                                            <label for="mobile_phone">เบอร์โทรศัพท์(มือถือ)</label>
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="mobile_phone" name="mobile_phone" maxlength="20" value="<?=$x_mobile_phone;?>" placeholder="กรุณากรอกเบอร์โทรศัพท์">
                                        </div>

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <label for="email">อีเมล</label>
                                            <input type="email" class="form-control" style="border-color: #f5b6b6;" id="email" name="email" maxlength="100" value="<?=$x_email;?>" placeholder="กรุณากรอกอีเมล">
                                        </div>

                                        <!-- Address -->
                                        <div class="form-group">
                                            <label for="business_phone">PBX Exten</label>
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="business_phone" name="business_phone" maxlength="7" value="<?=$x_business_phone;?>" placeholder="กรุณากรอกเบอร์ pbx extension">
                                        </div>

                                        <!-- Country (Default to Thailand) -->
                                        <div class="form-group">
                                            <label for="user_type">ประเภทผู้ใช้งาน</label>
                                            <select name="user_type" id="user_type" class="form-control">
                                                <option value="1" <?= isset($x_user_type) && $x_user_type == '1' ? 'selected' : ''; ?>>Administrator</option>
                                                <option value="2" <?= isset($x_user_type) && $x_user_type == '2' ? 'selected' : ''; ?>>User</option>
                                            </select>
                                        </div>

                                        <!-- Customer Status (Active / Inactive) -->
                                        <div class="form-group">
                                            <label for="status">สถานะ</label>
                                            <select class="form-control" id="cstatus" name="cstatus">
                                                <option value="1" <?= isset($x_cstatus) && $x_cstatus == '1' ? 'selected' : ''; ?>>Active</option>
                                                <option value="0" <?= isset($x_cstatus) && $x_cstatus == '0' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>                                        
                                        
                                    </form>
                                </div>
                            </div>

                        </div>

                    <!-- /.card -->
                    </div>
                    
                </div>
                <!-- /.card-body -->

        </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <span class="fas fa-times-circle"></span> ปิด</button>

                <?php
                    if($Permission != 'xx'){
                        echo "<button type=\"button\" class=\"btn btn-primary\" id=\"btnSave\" onclick=\"FcUsersSaveOrEdit();\"><span class=\"fas fa-save\"></span> บันทึก</button>";
                    }
                ?>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>

    /*********************************************************
     * ทำงานเมื่อคลิกปุ่ม บันทึก จาก modal users
     *********************************************************/
    function FcUsersSaveOrEdit() {
        // ดึงข้อมูลจากฟอร์มและแปลงเป็น query string
        const formData = $('#frmUsers').serialize();

        // ตรวจสอบค่าที่จำเป็นต้องกรอก
        const full_name = $('#full_name').val();
        const username = $('#username').val();
        const password = $('#password').val();
        const confirmpassword = $('#confirmpassword').val();
        const mode = $('#mode').val();

        if (!full_name || !username) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน <br> Please fill in all required fields.');
            return;
        }

        if (mode === 'new') {
            if (password === '') {
                toastr.error('กรุณากรอกรหัสผ่าน <br> Password is required.');
                return;
            }

            if (confirmpassword === '') {
                toastr.error('กรุณายืนยันรหัสผ่าน <br> Confirm Password is required.');
                return;
            }

            if (password !== confirmpassword) {
                toastr.error('ยืนยันรหัสผ่านไม่ถูกต้อง ลองใหม่อีกครั้ง <br> Password not match.');
                return;
            }
        }
    

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
        $.ajax({
            url: $('#frmUsers').attr('action'), // ใช้ URL จากค่า action ของฟอร์ม
            type: 'POST', // ใช้วิธี POST ในการส่งข้อมูล
            data: formData, // ส่งข้อมูลที่แปลงเป็น query string
            dataType: 'json', // ให้ jQuery แปลง response เป็น JSON อัตโนมัติ
            success: function(response) {
                try {
                    // ตรวจสอบว่าการบันทึกข้อมูลสำเร็จหรือไม่
                    if (response.rCode === 200 && response.rMsg === 'Success') {
                        // แสดง SweetAlert เมื่อบันทึกสำเร็จ
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            text: 'ข้อมูลลูกค้าถูกบันทึกแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        $('#UsersModal').modal('hide'); // ปิด modal หลังจากบันทึกสำเร็จ
                        FcReloadUsersTable();

                    }else if(response.rCode === 200 && response.rMsg === 'ExistingUser'){
                        // แสดง SweetAlert เมื่อบันทึกไม่สำเร็จ
                        Swal.fire({
                            icon: 'warning',
                            title: 'เกิดข้อผิดพลาด!',
                            text: response.rData,
                        });
                    }else{
                        // แสดง SweetAlert เมื่อบันทึกไม่สำเร็จ
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถบันทึกข้อมูลลูกค้าได้',
                        });
                    }
                } catch (error) {
                    // จัดการข้อผิดพลาดหากมีปัญหาในการแปลง JSON
                    console.error('JSON Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถประมวลผลข้อมูลจากเซิร์ฟเวอร์ได้.',
                    });
                }
            },
            error: function(xhr, status, error) {
                // จัดการข้อผิดพลาดหากมีปัญหาในการเชื่อมต่อ AJAX
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์.',
                });
            }
        });
    }


</script>