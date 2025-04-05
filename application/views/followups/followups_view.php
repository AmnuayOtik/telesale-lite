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
                                <button type="button" class="btn btn-success btn-sm shadow-btn" id="cmdSaveButton" style="border-radius: 25px 25px 25px 25px;"><span class="fas fa-save"></span> บันทึก</button>    
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="col-md-12" style="padding: 10px">
                                <!-- line_account -->
                                <div class="form-group">
                                    <label for="line_account">บันทึกผลการโทร</label>
                                    <textarea name="call_result" id="call_result" class="form-control" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12" style="padding: 10px">
                                <!-- line_account -->
                                <div class="form-group">
                                    <label for="line_account">แจ้งผลผ่านทางไลน์</label>
                                    <textarea name="call_result" id="call_result" class="form-control" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12" style="padding: 10px">
                                <!-- line_account -->
                                <div class="form-group">
                                    <label for="line_account">หมายเหตุ</label>
                                    <textarea name="call_result" id="call_result" class="form-control" rows="5"></textarea>
                                </div>
                            </div>


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
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?=$customer['phone_number'];?>" readonly>
                                            </div>
                                        </div>                                       
                                    </div> 

                                      
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success shadow-btn" id="startButton" style="border-radius:60px;width: 100%;height: 80px;font-size: 23px;font-weight: bold;"><span class="fas fa-phone-alt"></span> โทร</button>
                                    </div>

                                    <div id="time" style="border:1px solid #ee44ee;border-radius: 60px;padding: 6px;font-size: 39px;text-align: center;margin-bottom:10px;">
                                        <p style="margin-bottom: 0rem;">เวลา: <span id="timer">00:00:00</span></p>
                                    </div>
                                    
                                    
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
        if ($(this).hasClass('btn-success')) {
            // ถ้ามีคลาส 'btn-success' (สถานะเริ่ม)
            $(this).removeClass('btn-success').addClass('btn-danger').html('<span class="fas fa-phone-slash"></span> หยุด');
            // เคลียร์เวลาเป็นศูนย์ก่อนเริ่มจับเวลา
            totalSeconds = 0;
            $('#timer').text(formatTime(totalSeconds));
            startTimer(); // เริ่มจับเวลา
        } else {
            // ถ้าไม่มีคลาส 'btn-success' (สถานะหยุด)
            $(this).removeClass('btn-warning').addClass('btn-success').html('<span class="fas fa-phone-alt"></span> โทร');
            stopTimer(); // หยุดจับเวลา
        }
    });

</script>