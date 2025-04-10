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
              <li class="breadcrumb-item"><a href="<?=base_url('Dashboard');?>">Home</a></li>
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
                <div class="col-12" id="ProductModalSelector">
                    <!-- Modal Open here -->                    
                </div>
            </div>
            <hr style="margin-top: -12px;">
            <!-- Main row -->
            <div class="row">
                <!-- left col -->
                <div class="col-md-8"> 
                    
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายละเอียดลูกค้า || Customer Information</h3>
                            <div class="card-tools">                            
                                
                            </div>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="Followups/FcSaveOrEdit" name="frmFollowups" id="frmFollowups">
                            
                            
                                <fieldset style="border: 1px solid rgb(169, 166, 170);border-radius: 9px;margin-bottom: 13px;padding: 15px;">
                                    <legend style="width: 214px;background-color:rgb(255, 184, 179);border-radius: 5px;padding: 5px;text-align: left;font-size: 18px;font-weight: bold;margin-left: 9px;">&nbsp;&nbsp;&nbsp;ข้อมูลผลการโทรครั้งนี้</legend>
                                    
                                    <div class="row">
                                        <div class="col-md-6" style="padding: 10px">
                                            <!-- line_account -->
                                            <div class="form-group">
                                                <label for="line_account">ผลการโทร (Call Result)</label>
                                                
                                                <input type="hidden" name="customer_id" id="customer_id" value="<?=$customer['customer_id'];?>" readonly>                                                                                  
                                                <select name="call_result" id="call_result" class="form-control">
                                                    <option value="">-- เลือกผลการโทร --</option>
                                                    <?php 
                                                        foreach($call_result_master as $row){

                                                            if($customer['call_result'] == $row['call_name']){
                                                                echo "<option value=\"".$row['call_name']."\" selected>" . $row['call_name'] . "</option>";
                                                            }else{
                                                                echo "<option value=\"".$row['call_name']."\">".$row['call_name']."</option>";
                                                            }
                                                        }
                                                    ?>
                                                   
                                                </select>                                   
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="padding: 10px">

                                            <!-- line_account -->
                                            <div class="form-group">
                                                <label for="cstatus">สถานะรายการ (Status)</label>
                                                <select class="form-control" id="cstatus" name="cstatus">
                                                    <option value="Waiting" <?= isset($customer['cstatus']) && $customer['cstatus'] == 'Waiting' ? 'selected' : ''; ?>>รอดำเนินการ</option>
                                                    <option value="Incomplete" <?= isset($customer['cstatus']) && $customer['cstatus'] == 'Incomplete' ? 'selected' : ''; ?>>ติดต่อลูกค้าไม่สำเร็จ</option>
                                                    <option value="Postpone" <?= isset($customer['cstatus']) && $customer['cstatus'] == 'Postpone' ? 'selected' : ''; ?>>ขอเลื่อน</option>
                                                    <option value="Finished" <?= isset($customer['cstatus']) && $customer['cstatus'] == 'Finished' ? 'selected' : ''; ?>>โทรเสร็จสิ้น</option>
                                                </select>
                                            </div> 

                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- line_account -->
                                            <div class="form-group">
                                                <label for="call_result_note">ระบุผลการโทรอื่นๆ</label>
                                                <textarea name="call_result_note" id="call_result_note" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </fieldset>

                                <fieldset style="border: 1px solid rgb(169, 166, 170);border-radius: 9px;padding: 15px;">
                                    <legend style="width: 214px;background-color:rgb(255, 184, 179);border-radius: 5px;padding: 5px;text-align: left;font-size: 18px;font-weight: bold;margin-left: 9px;">&nbsp;&nbsp;&nbsp;แจ้งผลการโทรทางไลน์</legend>
                                    
                                    <div class="col-md-6" style="padding: 10px">
                                        <!-- line_account -->
                                        <div class="form-group">
                                            <label for="line_account">แจ้งผลผ่านทางไลน์ (Line inform)</label>
                                            <select name="line_account" id="line_account" class="form-control">
                                                <option value="">-- เลือกผลการโทร --</option>
                                                <option value="Sms">Sms</option>
                                                <option value="ไลน์ลูกค้า">ไลน์ลูกค้า</option>                                            
                                                <option value="อื่นๆ">อื่นๆ</option>
                                            </select>                                   
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding: 10px">
                                        <!-- line_account -->
                                        <div class="form-group">
                                            <label for="line_account_note">ระบุแจ้งผลทางไลน์อื่นๆ</label>
                                            <textarea name="line_account_note" id="line_account_note" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                    
                                </fieldset>

                                <div class="col-md-12" style="padding: 10px">
                                    <!-- line_account -->
                                    <div class="form-group">
                                        <label for="note">หมายเหตุ</label>
                                        <textarea name="note" id="note" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>                    
                    <!-- /.card -->

                </div>
                
                <!-- right col -->
                <div class="col-md-4" style="position: sticky;top: 20px; /* ระยะห่างจากขอบบน */height: fit-content; /* ให้ขนาดพอดีกับเนื้อหา */">
                                    
                    <div class="card">
                        <div class="card-header border-0" style="background-color: #e4cb83;">
                            <h3 class="card-title"><span class="fas fa-user-circle"></span> รายละเอียดลูกค้า : <span style="font-weight:bold;">คุณ<?=$customer['full_name'];?></span></h3>
                            <div class="card-tools">                            
                                
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12" style="padding: 25px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="order_id">เลขลำดับเอกสาร | Document Number</label>
                                            <div class="input-group mb-3">
                                                
                                                <div class="input-group-prepend">
                                                <span class="input-group-text"><span class="fas fa-key"></span></span>
                                                </div>
                                                <input type="hidden" name="mode" id="mode" value="new">
                                                <input type="text" class="form-control" id="order_id" name="order_id" value="<?=$customer['customer_id'];?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">                                            
                                            <label for="customer_id">รหัสอ้างอิงลูกค้า</label>
                                            <div class="input-group mb-3">
                                                
                                                <div class="input-group-prepend">
                                                <span class="input-group-text"><span class="fas fa-id-badge"></span></span>
                                                </div>
                                                <input type="text" class="form-control" id="ref_user_id" name="ref_user_id" value="<?=$customer['ref_user_id'];?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="full_name">ชื่อลูกค้า</label>
                                            <div class="input-group mb-3">                                                
                                                <div class="input-group-prepend">
                                                <span class="input-group-text"><span class="fas fa-user-circle"></span></span>
                                                </div>
                                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?=$customer['full_name'];?>" readonly>
                                            </div>
                                        </div>                                        
                                    </div>                                     
                                    
                                    <div class="form-group">
                                        <label for="line_account">ชื่อบัญชีไลน์ (Line Account)</label>
                                        <input type="text" class="form-control" id="line_account" name="line_account" value="<?=$customer['line_account'];?>" readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-7">                                            
                                            <label for="missed_deposit">ขาดฝาก</label>
                                            <div class="input-group mb-3">                                                
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><span class="fas fa-envelope-open"></span></span>
                                                </div>
                                                <input type="text" class="form-control" id="missed_deposit" name="missed_deposit" value="<?=$customer['missed_deposit'];?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-5">                                            
                                            <label for="phone">โทรศัพท์</label>
                                            <div class="input-group mb-3">                                                
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><span class="fas fa-phone-alt"></span></span>
                                                </div>
                                                <?php if($this->session->userdata('is_admin') != true){?>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="**********" readonly>
                                                <?php }else{ ?>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?=$customer['phone_number'];?>" readonly>
                                                <?php } ?>

                                                
                                            </div>
                                        </div>                                       
                                    </div> 

                                      
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning shadow-btn" id="startButton" data-cid="<?=$customer['customer_id'];?>" style="border-radius:7px;width: 100%;height: 80px;font-size: 23px;font-weight: bold;margin-bottom: 15px;"><span class="fas fa-phone-alt"></span> โทร</button>
                                        <button type="button" class="btn btn-success shadow-btn" onclick="FcSaveOrEdit();" style="border-radius:7px;width: 100%;height: 80px;font-size: 23px;font-weight: bold;"><span class="fas fa-save"></span> บันทึกข้อมูล</button>
                                    </div>

                                    <div id="time" style="border:1px solid #ee44ee;border-radius: 7px;padding: 6px;font-size: 39px;text-align: center;margin-bottom:10px;display:none;">
                                        <p style="margin-bottom: 0rem;">เวลา: <span id="timer">00:00:00</span></p>
                                    </div>
                                    
                                    <!--
                                    <div class="row">                                    
                                        <div class="col-md-12">
                                            <div class="card" style="padding: 0px;">
                                                <div class="card-header border-0" style="background-color: antiquewhite;">รายละเอียดอื่นๆ</div>
                                                    <div class="card-body">
                                                   
                                                        <ul>
                                                            <li>มีความสนใจสินค้าประเภทเอลกอฮอดีน์</li>
                                                            <li>ชอบซื้อสินค้าที่มีของแถมฟรี</li>
                                                            <li>ลูกค้าเป็นผู้่ชาย อายุ 45 ปี ชอบแต่งรถยนต์</li>
                                                        </ul>                                                        
                                                    </div>
                                                </dvi>
                                            </div>
                                        </div>
                                    </div>
                                    -->

                                </div>
                            </div>
                        </div>
                    </div>                    
                    <!-- /.card -->

                </div>

            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>

    /*********************************************************
    * ฟังก์ชั่น ทำการคำนวนยอดรวม เมื่อโหลดหน้าเว็บ
    *********************************************************/  
    $(document).ready(function () {
        // คำนวณค่าเริ่มต้นเมื่อโหลดหน้าเว็บ        
    });

    let timerInterval;
    let totalSeconds = 0;

    // ฟังก์ชันสำหรับแปลงเวลาเป็นรูปแบบ hh:mm:ss
    function formatTime(seconds) {
        let hours = Math.floor(seconds / 3600);
        let minutes = Math.floor((seconds % 3600) / 60);
        let remainingSeconds = seconds % 60;

        // การเติม 0 หน้าเวลาที่น้อยกว่า 10
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        remainingSeconds = remainingSeconds < 10 ? '0' + remainingSeconds : remainingSeconds;

        return hours + ':' + minutes + ':' + remainingSeconds;
    }

    // ฟังก์ชันจับเวลา
    function startTimer() {
        timerInterval = setInterval(function() {
            totalSeconds++;
            $('#timer').text(formatTime(totalSeconds));
        }, 1000); // อัพเดททุก 1 วินาที
    }

    // ฟังก์ชันหยุดจับเวลา
    function stopTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
    }

    // ฟังก์ชันคลิกปุ่มเพื่อเริ่มและหยุดจับเวลา
    
    $('#startButton').click(function() {
        const cid = $(this).data('cid');
        console.log('CID:', cid);
        Dial(cid);
    });
    


    // ฟังก์ชันคลิกปุ่มเพื่อเริ่มและหยุดจับเวลา
    /*
    $('#startButton').click(function() {
        if ($(this).hasClass('btn-warning')) {
            // ถ้ามีคลาส 'btn-success' (สถานะเริ่ม)
            $(this).removeClass('btn-warning').addClass('btn-danger').html('<span class="fas fa-phone-alt"></span> หยุด');
            
            // เคลียร์เวลาเป็นศูนย์ก่อนเริ่มจับเวลา
            totalSeconds = 0;
            $('#timer').text(formatTime(totalSeconds));

            const cid = $(this).data('cid');
            console.log('CID:', cid);
            Dial(cid);
            startTimer(); // เริ่มจับเวลา

        } else {
            // ถ้าไม่มีคลาส 'btn-success' (สถานะหยุด)
            $(this).removeClass('btn-danger').addClass('btn-warning').html('<span class="fas fa-phone-alt"></span> โทร');
            stopTimer(); // หยุดจับเวลา
        }
    });
    */

    function Dial(cid = ''){
        
        if(cid == ''){
            toastr.error('ไม่พบข้อมูลลูกค้า','เกิดข้อผิดพลาด');
            return false;
        }

        console.log('กำลังโทรออกไปหาลูกค้า : ' + cid);

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์ผ่าน AJAX
        $.ajax({
            url: '<?=base_url('Followups/Dial');?>',
            type: 'POST', // ใช้วิธี POST ในการส่งข้อมูล
            data: { cid : cid }, // ส่งข้อมูลที่แปลงเป็น query string
            dataType: 'json', // ให้ jQuery แปลง response เป็น JSON อัตโนมัติ
            success: function(response) {
                try {
                    // ตรวจสอบว่าการบันทึกข้อมูลสำเร็จหรือไม่
                    if (response.rCode == 200 && response.status === 'success') {
                        toastr.success('ระบบกำลังโทรออกหาลูกค้า','แสดงการโทรออก');
                    } else {
                        toastr.error('ไม่สามารถโทรออกได้','เกิดข้อผิดพลาด');
                    }
                } catch (error) {
                    // จัดการข้อผิดพลาดหากมีปัญหาในการแปลง JSON
                    console.error('JSON Error:', error);
                    toastr.error('ไม่สามารถประมวลผลข้อมูลจากเซิร์ฟเวอร์ได้','เกิดข้อผิดพลาด');
                }
            },
            error: function(xhr, status, error) {
                // จัดการข้อผิดพลาดหากมีปัญหาในการเชื่อมต่อ AJAX
                console.error('AJAX Error:', error);
                toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์','เกิดข้อผิดพลาด');
            }
        });

    }

    function FcSaveOrEdit(){

        const formData = $('#frmFollowups').serialize();
        const call_result = $('#call_result').val();
        const cstatus = $('#cstatus').val();

        if (!call_result || !cstatus) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน <br> Please fill in all required fields.');
            return;
        }

        $.ajax({
            url: $('#frmFollowups').attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                try {
                    // ตรวจสอบว่าการบันทึกข้อมูลสำเร็จหรือไม่
                    if (response.rCode == 200 && response.rMsg === 'Success') {
                        // แสดง SweetAlert เมื่อบันทึกสำเร็จ
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            text: 'ข้อมูลลูกค้าถูกบันทึกแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
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
                    toastr.error('ไม่สามารถประมวลผลข้อมูลจากเซิร์ฟเวอร์ได้','เกิดข้อผิดพลาด');
                }
            },
            error: function(xhr, status, error) {
                // จัดการข้อผิดพลาดหากมีปัญหาในการเชื่อมต่อ AJAX
                console.error('AJAX Error:', error);
                toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์','เกิดข้อผิดพลาด');
            }
        });

    }
</script>