<!-- The Modal -->                    
<div class="modal fade" id="ModalProducts">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90vw; max-height: 90vh;">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">เลือกรายการสินค้า</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body" style="flex: 1; overflow-y: auto; padding: 8px; max-height: 100%;">
                <table class="table table-striped table-bordered" id="ProductsTable">
                    <thead class="thead-dark">
                        <tr> 
                            <th style="width:15%;">รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่สินค้า</th>
                            <th style="width:15%;">ราคา (บาท)</th>
                            <th style="width:10%; text-align: center;">เลือก</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $row){ ?>
                        <tr>
                            <td><?=$row->product_id;?></td>
                            <td><?=$row->product_name;?></td>
                            <td><?=$row->product_type;?></td>
                            <td><?=$row->price;?></td>
                            <td style="text-align: center;">
                                <button class="btn btn-success btn-sm select-product" 
                                    data-code="<?=$row->product_id;?>" 
                                    data-name="<?=$row->product_name;?>" 
                                    data-price="<?=$row->price;?>">
                                    <span class="fas fa-arrow-down"></span>
                                    เลือก
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>

        </div>    

    </div>
</div>


<script>
    
    // เมื่อเลือกสินค้าใน Modal แล้ว
    $(".select-product").on("click", function() {
        // ดึงข้อมูลจากปุ่มเลือกสินค้า
        var productCode = $(this).data("code");
        var productName = $(this).data("name");
        var productPrice = $(this).data("price");

        // ตรวจสอบว่ามีแถวที่เลือกไว้หรือไม่
        if (window.currentRow) {
            // ใส่ข้อมูลสินค้าลงในแถวที่เลือก
            window.currentRow.find("input[name='product_id[]']").val(productCode);
            window.currentRow.find("input[name='product_name[]']").val(productName);
            window.currentRow.find("input[name='price[]']").val(productPrice);
            window.currentRow.find("input[name='total[]']").val(productPrice);  // คำนวณราคาเริ่มต้น
                    
        }

    // ปิด Modal
        $('#ModalProducts').modal('hide');  
        calculateTotal();      
        
    });

    /*********************************************************
    * ฟังก์ชั่นแสดงตารางสินค้า
    *********************************************************/  
    $('#ProductsTable').DataTable({
        "pageLength": 10, // กำหนดให้แสดง 10 รายการเป็นค่าเริ่มต้น  
        "lengthChange": false, // ซ่อนตัวเลือกเปลี่ยนจำนวนรายการต่อหน้า
        "order": [[1, "desc"]], // เรียงลำดับคอลัมน์ที่ 1 (product_id) จากมากไปน้อย           
        "autoWidth": false, // ปิดการกำหนดความกว้างอัตโนมัติ   
        "language": {
            "search": "ค้นหา:",
            "info": "แสดงรายการที่ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "infoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",   
            "processing": "กำลังดำเนินการ...",
            "zeroRecords": "ไม่พบข้อมูล",         
            "paginate": {
                "first": "หน้าแรก",
                "last": "สุดท้าย",
                "next": "ต่อไป",
                "previous": "ก่อนหน้า"
            },
        }
    });


</script>