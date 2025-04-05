<?php

// ทำการตั้งค่า Default โหมด หากไม่มีการกำหนดเข้ามา
$Permission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : '';
$DisabledPermission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : 'disabled';

// เตรียมข้อมูลเพื่อแสดงรายการ
$x_customer_id = isset($customer['customer_id']) ? $customer['customer_id'] : '';
$x_full_name = isset($customer['full_name']) ? $customer['full_name'] : '';
$x_phone_number = isset($customer['phone_number']) ? $customer['phone_number'] : '';
$x_email = isset($customer['email']) ? $customer['email'] : '';
$x_address = isset($customer['address']) ? $customer['address'] : '';
$x_city = isset($customer['city']) ? $customer['city'] : '';
$x_state = isset($customer['state']) ? $customer['state'] : '';
$x_zip_code = isset($customer['zip_code']) ? $customer['zip_code'] : '';
$country = isset($customer['country']) ? $customer['country'] : '';
$x_status = isset($customer['status']) ? $customer['status'] : '';

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
                                            <label for="customer_id">รหัสลูกค้า</label>
                                            <input type="hidden" name="mode" id="mode" value="<?=$mode;?>">
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="customer_id" name="customer_id" maxlength="10" value="<?=$x_customer_id;?>" placeholder="Auto" readonly>
                                        </div>

                                        <!-- Full Name -->
                                        <div class="form-group">
                                            <label for="full_name">ชื่อลูกค้า</label>
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="full_name" name="full_name" maxlength="255" value="<?=$x_full_name;?>" placeholder="กรุณากรอกชื่อเต็มของลูกค้า" <?=$Permission;?>>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="form-group">
                                            <label for="phone_number">เบอร์โทรศัพท์</label>
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="phone_number" name="phone_number" maxlength="20" value="<?=$x_phone_number;?>" placeholder="กรุณากรอกเบอร์โทรศัพท์" <?=$Permission;?>>
                                        </div>

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <label for="email">อีเมล</label>
                                            <input type="email" class="form-control" style="border-color: #f5b6b6;" id="email" name="email" maxlength="100" value="<?=$x_email;?>" placeholder="กรุณากรอกอีเมล" <?=$Permission;?>>
                                        </div>

                                        <!-- Address -->
                                        <div class="form-group">
                                            <label for="address">ที่อยู่</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="กรุณากรอกที่อยู่"><?=$x_address;?></textarea>
                                        </div>

                                        <!-- City -->
                                        <div class="form-group">
                                            <label for="city">เมือง</label>
                                            <input type="text" class="form-control" id="city" name="city" maxlength="100" value="<?=$x_city;?>" placeholder="กรุณากรอกชื่อเมือง">
                                        </div>

                                        <!-- State -->
                                        <div class="form-group">
                                            <label for="state">รัฐ/จังหวัด</label>
                                            <input type="text" class="form-control" id="state" name="state" maxlength="100" value="<?=$x_state;?>" placeholder="กรุณากรอกชื่อรัฐ/จังหวัด">
                                        </div>

                                        <!-- Zip Code -->
                                        <div class="form-group">
                                            <label for="zip_code">รหัสไปรษณีย์</label>
                                            <input type="text" class="form-control" id="zip_code" name="zip_code" maxlength="20" value="<?=$x_zip_code;?>" placeholder="กรุณากรอกรหัสไปรษณีย์">
                                        </div>

                                        <!-- Country (Default to Thailand) -->
                                        <div class="form-group">
                                            <label for="country">ประเทศ</label>
                                            <input type="text" class="form-control" id="country" name="country" value="Thailand" >
                                        </div>

                                        <!-- Customer Status (Active / Inactive) -->
                                        <div class="form-group">
                                            <label for="status">สถานะลูกค้า</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?= isset($x_status) && $x_status == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?= isset($x_status) && $x_status == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
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