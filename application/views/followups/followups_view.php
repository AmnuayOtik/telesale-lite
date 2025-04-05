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
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>100/<small style="font-size:23px;color:yellow;">250</small></h3>

                        <p>จำนวนรายการที่ปิดยอดแล้ว/ทั้งหมด</p>
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
                        <h3>20</h3>

                        <p>จำนวนรายการให้ติดต่อกลับ</p>
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
                        <h3><?=number_format(285600, 2);?></h3>

                        <p>ยอดปิดวันนี้</p>
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
                        <h3>20</h3>

                        <p>จำนวนรายการที่ปิดไม่ได้</p>
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
                <div class="col-12" id="ProductModalSelector">
                    <!-- Modal Open here -->                    
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- left col -->
                <div class="col-md-8"> 
                    
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">แสดงรายชื่อสินค้า || Order Items</h3>
                            <div class="card-tools">                            
                                <button type="button" class="btn btn-success btn-sm shadow-btn" id="cmdSaveButton" style="border-radius: 25px 25px 25px 25px;"><span class="fas fa-save"></span> บันทึก</button>    
                                <!--<button type="button" class="btn btn-danger btn-sm" style="border-radius: 25px 25px 25px 25px;"><span class="fas fa-trash-alt"></span> ลบ</button>-->
                                <button type="button" id="addRow" class="btn btn-sm btn-primary shadow-btn" style="border-radius: 25px 25px 25px 25px;"><span class="fas fa-plus"></span> เพิ่มสินค้า</button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>                                        
                                        <th style="width:16%;text-align:left;">รหัสสินค้า</th>
                                        <th style="width:30%;">ชื่อสินค้า</th>
                                        <th style="width:12%;text-align:right;">ราคา</th>
                                        <th style="width:10%;text-align:right;">จำนวน</th>
                                        <th style="width:10%;text-align:right;">ส่วนลด</th>
                                        <th style="width:13%;text-align:right;">รวม</th>                                        
                                        <th style="width:15%;text-align:right;white-space: nowrap;"></th>
                                    </tr>
                                </thead>
                                <tbody id="CustomerTableBody">
                                    <?php for($i=0;$i < 5;$i++) { ?>
                                        <tr>                    
                                            <td>                        
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="product_id[]" id="product_id" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-default" type="button" onclick="FcOpenModalProducts(this);"><span class="fas fa-search"></span></button>
                                                    </div>
                                                </div>                                               
                                            </td>
                                            <td>
                                                <input type="text" name="product_name[]" value="" class="form-control" readonly>
                                            </td>
                                            <td style="text-align:right;" class="price">
                                                <input type="text" name="price[]" value="0" class="form-control" style="text-align: right;">
                                            </td>
                                            <td style="text-align:right;" class="quantity">
                                                <input type="number" name="quantity[]" value="1" class="form-control" style="text-align: right;">
                                            </td>
                                            <td style="text-align:right;" class="discount">
                                                <input type="number" name="discount[]" value="0" class="form-control" style="text-align: right;">
                                            </td>
                                            <td style="text-align:right;" class="total">
                                                <input type="text" name="total[]" value="0" class="form-control" style="text-align: right;" readonly>
                                            </td>
                                            <td style="text-align:right;white-space: nowrap;">
                                                <button type="button" class="btn btn-danger btn-sm removeRow" style="border-radius: 25px;">
                                                    <span class="fas fa-trash-alt"></span> ลบ
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align:right;font-weight:bold"><strong>รวมทั้งหมด:</strong></td>                                        
                                        <td><strong style="font-weight:bold;" id="total_price">4,500.00</strong> บาท</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>                    
                    <!-- /.card -->

                </div>
                
                <!-- right col -->
                <div class="col-md-4" style="position: sticky;top: 20px; /* ระยะห่างจากขอบบน */height: fit-content; /* ให้ขนาดพอดีกับเนื้อหา */">
                                    
                    <div class="card">
                        <div class="card-header border-0" style="background-color: #e4cb83;">
                            <h3 class="card-title"><span class="fas fa-user-circle"></span> รายละเอียดลูกค้า : <span style="font-weight:bold;">คุณอำนวย ปิ่นทอง</span></h3>
                            <div class="card-tools">                            
                                
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-md-12" style="padding: 25px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="order_id">รหัสเอกสารขายสินค้า | Sale Order number</label>
                                            <div class="input-group mb-3">
                                                
                                                <div class="input-group-prepend">
                                                <span class="input-group-text"><span class="fas fa-key"></span></span>
                                                </div>
                                                <input type="hidden" name="mode" id="mode" value="new">
                                                <input type="text" class="form-control" id="order_id" name="order_id" value="SO-##########" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">                                            
                                            <label for="customer_id">รหัสลูกค้า</label>
                                            <div class="input-group mb-3">
                                                
                                                <div class="input-group-prepend">
                                                <span class="input-group-text"><span class="fas fa-id-badge"></span></span>
                                                </div>
                                                <input type="text" class="form-control" id="customer_id" name="customer_id" value="<?=$customer['customer_id'];?>" readonly>
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
                                        <label for="address">ที่อยู่ปัจจุบัน</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="กรุณากรอกที่อยู่" readonly><?=$customer['address']?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-7">                                            
                                            <label for="email">อีเมล</label>
                                            <div class="input-group mb-3">                                                
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><span class="fas fa-envelope-open"></span></span>
                                                </div>
                                                <input type="text" class="form-control" id="email" name="email" value="<?=$customer['email'];?>" readonly>
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
                                        <button type="button" class="btn btn-success shadow-btn" style="width: 100%;height: 80px;font-size: 23px;font-weight: bold;"><span class="fas fa-phone-alt"></span> Call</button>
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
        calculateTotal();
    });

    /*********************************************************
    * ประกาศตัวแปรนับจำนวนแถวในตารางสินค้า
    *********************************************************/ 
    let rowCount = $("#CustomerTableBody tr").length + 1;

    /*********************************************************
    * ฟังก์ชั่น คำนวณ total ของแต่ละแถว
    *********************************************************/    
    function calculateTotal() {
        $("#CustomerTableBody tr").each(function () {
            let row = $(this);
            let price = parseFloat(row.find(".price input").val()) || 0;
            let quantity = parseInt(row.find(".quantity input").val()) || 0;
            let discount = parseFloat(row.find(".discount input").val()) || 0;

            let total = (price * quantity) - discount;
            row.find(".total input").val(total.toFixed(2));
        });

        // คำนวณ Grand Total
        calculateGrandTotal();
    }

    /*********************************************************
    * ฟังก์ชั่น คำนวณ Grand Total
    *********************************************************/    
    function calculateGrandTotal() {
        let grandTotal = 0;
        $("#CustomerTableBody .total input").each(function () {
            grandTotal += parseFloat($(this).val()) || 0;
        });

        $("#total_price").text(grandTotal.toFixed(2));
    }

    /*********************************************************
    * ฟังก์ชั่น คำนวณเมื่อมีการเปลี่ยนแปลงค่า price, quantity, discount
    *********************************************************/
    $("#CustomerTableBody").on("input", ".price input, .quantity input, .discount input", function () {
        calculateTotal();
    });

    /*********************************************************
    * ฟังก์ชั่น เพิ่มรายการใหม่เมื่อกดปุ่ม
    *********************************************************/    
    $("#addRow").click(function () {
        let newRow = `
            <tr>                    
                <td>                        
                    <div class="input-group">
                        <input type="text" class="form-control" name="product_id[]" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-default" type="button" onclick="FcOpenModalProducts(this);"><span class="fas fa-search"></span></button>
                        </div>
                    </div>
                </td>
                <td>
                    <input type="text" name="product_name[]" value="" class="form-control" readonly>
                </td>
                <td style="text-align:right;" class="price">
                    <input type="text" name="price[]" value="0" class="form-control" style="text-align: right;">
                </td>
                <td style="text-align:right;" class="quantity">
                    <input type="number" name="quantity[]" value="1" class="form-control" style="text-align: right;">
                </td>
                <td style="text-align:right;" class="discount">
                    <input type="number" name="discount[]" value="0" class="form-control" style="text-align: right;">
                </td>
                <td style="text-align:right;" class="total">
                    <input type="text" name="total[]" value="0" class="form-control" style="text-align: right;" readonly>
                </td>
                <td style="text-align:right;">
                    <button type="button" class="btn btn-danger btn-sm removeRow" style="border-radius: 25px;white-space: nowrap;">
                        <span class="fas fa-trash-alt"></span> ลบ
                    </button>
                </td>
            </tr>`;
        
        $("#CustomerTableBody").append(newRow);
        rowCount++;
        calculateGrandTotal(); // อัปเดต Grand Total เมื่อเพิ่มรายการใหม่
    });

    /*********************************************************
    * ฟังก์ชั่น ลบแถว
    *********************************************************/    
    $("#CustomerTableBody").on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
        calculateGrandTotal(); // อัปเดต Grand Total หลังจากลบแถว
    });

    /*********************************************************
    * ฟังก์ชันเปิด Modal และส่งแถวที่คลิกมาเพื่อเปลี่ยนข้อมูลสินค้า
    *********************************************************/
    function FcOpenModalProducts(button) {
        console.log("กำลังกดเปิดฟอร์มเลือกสินค้า");
        // ตรวจสอบว่าเมื่อคลิกปุ่มแล้วฟังก์ชันทำงาน        
        
        $.get('Products/ProductModallist', function(response) {
            $('#ProductModalSelector').html(response);
            $('#ModalProducts').modal('show');
        }).fail(function() {
            toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });

        // เก็บอ้างอิงของแถวที่ผู้ใช้คลิกเพื่อเปลี่ยนข้อมูลสินค้า
        window.currentRow = $(button).closest("tr");

    }

    /*********************************************************
    * ฟังก์ชั่น ฟังก์ชันสำหรับลบแถวจากตาราง
    *********************************************************/
    $(".removeRow").on("click", function() {
        // ลบแถวที่ผู้ใช้เลือก
        $(this).closest("tr").remove();
        calculateTotal();
    });

    /*********************************************************
    * ฟังก์ชั่น เมื่อกดปุ่ม "บันทึก"
    *********************************************************/
    $("#cmdSaveButton").on("click", function() {

        let mode = $('#mode').val();
        // อ่านข้อมูล Header
        var order_header = {
            order_id: $("#order_id").val(),
            customer_id: $("#customer_id").val(),
            full_name: $("#full_name").val(),
            address: $("#address").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            total_price: $("#total_price").text().trim()          
        };

        // เก็บข้อมูลทั้งหมดในตาราง
        var order_detail = [];
        
        // ลูปผ่านแต่ละแถวในตารางเพื่อดึงข้อมูลจาก input
        $('#CustomerTableBody tr').each(function() {
            var product_id = $(this).find("input[name='product_id[]']").val();
            
            // ตรวจสอบว่า product_id ไม่ว่างเปล่า
            if (product_id) {  // ถ้ามี product_id
                var product = {
                    product_id: product_id,
                    product_name: $(this).find("input[name='product_name[]']").val(),
                    price: $(this).find("input[name='price[]']").val(),
                    quantity: $(this).find("input[name='quantity[]']").val(),
                    discount: $(this).find("input[name='discount[]']").val(),
                    total: $(this).find("input[name='total[]']").val(),
                };
                order_detail.push(product);  // เก็บข้อมูลสินค้าลงใน array
            }
        });

        // ตรวจสอบว่ามีสินค้าใน products หรือไม่
        if (order_detail.length > 0) {
            // ส่งข้อมูลไปยัง Controller ผ่าน AJAX
            $.ajax({
                url: "<?php echo base_url('FollowUps/FcSaveOrder'); ?>", // URL ของ Controller ที่ใช้บันทึกข้อมูล
                method: "POST",
                data: {
                    mode: mode,
                    order_header: order_header,   // ส่งข้อมูล header
                    order_detail: order_detail  // ส่งข้อมูล detail
                },
                success: function(response) {
                    // ประมวลผลเมื่อบันทึกเสร็จ
                    console.log(response);

                    if(response.rCode == 200 && response.rMsg == 'Success'){

                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ!',
                            text: 'ข้อมูลลูกค้าถูกบันทึกแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#mode').val('edit');
                        $('#order_id').val(response.rData.order_header.order_id);                        

                    }else{
                        
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง.',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        } else {
            // ถ้าไม่มีข้อมูลให้บันทึก
            Swal.fire({
                title: 'ไม่มีข้อมูล!',
                text: 'กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ.',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
            });
        }
    });

</script>