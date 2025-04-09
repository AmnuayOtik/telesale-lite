<?php

// ทำการตั้งค่า Default โหมด หากไม่มีการกำหนดเข้ามา
$Permission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : '';
$DisabledPermission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : 'disabled';

// เตรียมข้อมูลเพื่อแสดงรายการ
$x_customer_id = isset($customer['customer_id']) ? $customer['customer_id'] : '';
$x_ref_user_id = isset($customer['ref_user_id']) ? $customer['ref_user_id'] : '';
$x_full_name = isset($customer['full_name']) ? $customer['full_name'] : '';
$x_phone_number = isset($customer['phone_number']) ? $customer['phone_number'] : '';
$x_line_account = isset($customer['line_account']) ? $customer['line_account'] : '';
$x_missed_deposit = isset($customer['missed_deposit']) ? $customer['missed_deposit'] : '';
$x_last_activity = isset($customer['last_activity']) ? $customer['last_activity'] : '';
$x_cstatus = isset($customer['cstatus']) ? $customer['cstatus'] : '';
$user_id = $this->session->userdata('user_id');

?>

<div class="modal fade" id="CustomerModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: beige;">
                <h4 class="modal-title">ฟอร์มบันทึกลูกค้า | Customer Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="false">&times;</span>
                </button>
            </div>
        <div class="modal-body" style="max-height:765px; overflow-y: auto;">                            
            
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

                                    <form name="frmCustomer" action="Customer/FcSaveOrEdit" method="post" id="frmCustomer">

                                        <!-- Customer ID (Auto generated) -->
                                        <div class="form-group">
                                            <label for="customer_id">เลขรายการ (Number)</label>
                                            <input type="hidden" name="mode" id="mode" value="<?=$mode;?>">
                                            <input type="hidden" name="user_id" id="user_id" value="<?=$user_id;?>">
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="customer_id" name="customer_id" maxlength="10" value="<?=$x_customer_id;?>" placeholder="Auto" readonly>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- Full Name -->
                                                <div class="form-group">
                                                    <label for="full_name">ชื่อลูกค้า (Full Name)</label>
                                                    <input type="text" class="form-control" style="border-color: #f5b6b6;" id="full_name" name="full_name" maxlength="255" value="<?=$x_full_name;?>" placeholder="" <?=$Permission;?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Phone Number -->
                                                <div class="form-group">
                                                    <label for="phone_number">เบอร์โทรศัพท์ (Mobile Number)</label>
                                                    <?php
                                                        if($this->session->userdata('is_admin') == true){
                                                            $x_phone_number =$x_phone_number;
                                                        }else{
                                                            $x_phone_number = '**********'; 
                                                        }
                                                    ?>
                                                    <input type="text" class="form-control" style="border-color: #f5b6b6;" id="phone_number" name="phone_number" maxlength="20" value="<?=$x_phone_number;?>" placeholder="" <?=$Permission;?>>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- ref_user_id -->
                                                <div class="form-group">
                                                    <label for="ref_user_id">อ้างอิงรหัสลูกค้า (Reference Number)</label>
                                                    <input type="text" class="form-control" id="ref_user_id" name="ref_user_id" maxlength="255" value="<?=$x_ref_user_id;?>" placeholder="" <?=$Permission;?>>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- line_account -->
                                                <div class="form-group">
                                                    <label for="line_account">บัญชีไลน์ (Line Account)</label>
                                                    <input type="email" class="form-control" id="line_account" name="line_account" maxlength="100" value="<?=$x_line_account;?>" placeholder="" <?=$Permission;?>>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- missed_deposit -->
                                        <div class="form-group">
                                            <label for="missed_deposit">ขาดฝาก (Missed Deposit)</label>                                            
                                            <input type="text" class="form-control" id="missed_deposit" name="missed_deposit" maxlength="100" value="<?=$x_missed_deposit;?>" placeholder="" <?=$Permission;?>>
                                        </div>

                                        <!-- last_activity -->
                                        <div class="form-group">
                                            <label for="last_activity">รายการเล่น/เหตุผลครั้งก่อนที่ตาม (Last Activity)</label>
                                            <input type="text" class="form-control" id="last_activity" name="last_activity" maxlength="100" value="<?=$x_last_activity;?>" placeholder="">
                                        </div> 
                                        
                                        <!-- Assign to -->
                                        <div class="form-group">
                                            <label for="cstatus">ผู้รับผิดชอบ (Assign to)</label>
                                            <select class="form-control" id="user_id" name="user_id">
                                               <?php foreach($users as $user) {
                                                    if($user->user_id == $user_id){
                                                        echo "<option value=\"".$user->user_id."\" selected>".$user->full_name."</option>";
                                                    }else{
                                                        echo "<option value=\"".$user->user_id."\">".$user->full_name."</option>";
                                                    }
                                                    
                                               } 
                                               ?>
                                            </select>
                                        </div>  

                                        <!-- Customer Status (Active / Inactive) -->
                                        <div class="form-group">
                                            <label for="cstatus">สถานะรายการ (Status)</label>
                                            <select class="form-control" id="cstatus" name="cstatus">
                                                <option value="Waiting" <?= isset($x_cstatus) && $x_cstatus == 'Waiting' ? 'selected' : ''; ?>>รอดำเนินการ</option>    
                                                <option value="Incomplete" <?= isset($x_cstatus) && $x_cstatus == 'Incomplete' ? 'selected' : ''; ?>>ติดต่อลูกค้าไม่สำเร็จ</option>
                                                <option value="Postpone" <?= isset($x_cstatus) && $x_cstatus == 'Postpone' ? 'selected' : ''; ?>>ขอเลื่อน</option>                                                
                                                <option value="Finished" <?= isset($x_cstatus) && $x_cstatus == 'Finished' ? 'selected' : ''; ?>>โทรเสร็จสิ้น</option>
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
                        echo "<button type=\"button\" class=\"btn btn-primary\" id=\"btnSave\" onclick=\"fcCustomerSaveOrEdit();\"><span class=\"fas fa-save\"></span> บันทึก</button>";
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
    function fcCustomerSaveOrEdit() {
        // ดึงข้อมูลจากฟอร์มและแปลงเป็น query string
        const formData = $('#frmCustomer').serialize();

        // ตรวจสอบค่าที่จำเป็นต้องกรอก
        const full_name = $('#full_name').val();
        const phone_number = $('#phone_number').val();

        if (!full_name || !phone_number) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน <br> Please fill in all required fields.');
            return;
        }

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
        $.ajax({
            url: $('#frmCustomer').attr('action'), // ใช้ URL จากค่า action ของฟอร์ม
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
                        
                        $('#CustomerModal').modal('hide'); // ปิด modal หลังจากบันทึกสำเร็จ
                        fcReloadCustomerTable();
                        FcGetTodayStatByUserID();

                    } else {
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