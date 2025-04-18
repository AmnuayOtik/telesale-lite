<?php

// ทำการตั้งค่า Default โหมด หากไม่มีการกำหนดเข้ามา
$Permission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : '';
$DisabledPermission = isset($mode) && ($mode === 'update' || $mode === 'new') ? '' : 'disabled';

// เตรียมข้อมูลเพื่อแสดงรายการ
$x_id = isset($callinform['id']) ? $callinform['id'] : '';
$x_name_th = isset($callinform['name_th']) ? $callinform['name_th'] : '';
$user_id = $this->session->userdata('user_id');

?>

<div class="modal fade" id="CallInformModal">
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
                        
                        <div class="card-body" id="FormCallResult">
                            
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

                                    <form name="frmCallInform" action="CallInform/FcSaveOrEdit" method="post" id="frmCallInform">

                                        <!-- Customer ID (Auto generated) -->
                                        <div class="form-group">
                                            <label for="customer_id">เลขรายการ (Number)</label>
                                            <input type="hidden" name="mode" id="mode" value="<?=$mode;?>">                                            
                                            <input type="text" class="form-control" style="border-color: #f5b6b6;" id="id" name="id" maxlength="10" value="<?=$x_id;?>" placeholder="Auto" readonly>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- name_th -->
                                                <div class="form-group">
                                                    <label for="name_th">ชื่อรายการ</label>
                                                    <input type="text" class="form-control" id="name_th" name="name_th" maxlength="100" value="<?=$x_name_th;?>" placeholder="" <?=$Permission;?>>
                                                </div>
                                            </div>                                            
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
                        echo "<button type=\"button\" class=\"btn btn-primary\" id=\"btnSave\" onclick=\"FcCallResultSaveOrEdit();\"><span class=\"fas fa-save\"></span> บันทึก</button>";
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
    function FcCallResultSaveOrEdit() {
        // ดึงข้อมูลจากฟอร์มและแปลงเป็น query string
        const formData = $('#frmCallInform').serialize();

        // ตรวจสอบค่าที่จำเป็นต้องกรอก
        const name_th = $('#name_th').val();        

        if (!name_th) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน <br> Please fill in all required fields.');
            return;
        }

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
        $.ajax({
            url: $('#frmCallInform').attr('action'), // ใช้ URL จากค่า action ของฟอร์ม
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
                            text: 'ข้อมูลถูกบันทึกแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        $('#CallInformModal').modal('hide'); // ปิด modal หลังจากบันทึกสำเร็จ
                        fcReloadCallInformTable();                        

                    } else {
                        // แสดง SweetAlert เมื่อบันทึกไม่สำเร็จ
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถบันทึกข้อมูลได้',
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