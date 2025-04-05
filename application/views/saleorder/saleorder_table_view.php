
<table class="table table-striped table-valign-middle" id="SaleOrderTable">
    <thead>
        <tr>
            <th>#</th>
            <th style="text-align:left;">รหัสเอกสาร</th>
            <th style="width:20%;">ชื่อลูกค้า</th>
            <th style="text-align:right:">ราคาขาย</th>
            <th>เบอร์โทรศัพท์</th>
            <th style="text-align:center;">สถานะ</th>
            <th style="width:15%;">วันที่สร้างเอกสาร</th>
            <th style="width:20%;text-align:right;">ดำเนินการ</th>
        </tr>
    </thead>
    <tbody id="SaleOrderTableBody">

        <?php foreach($saleorder as $row){ ?>                                 
            <tr>
                <td><input type="checkbox" name="order_id[]" id="<?=$row->order_id;?>" value="<?=$row->order_id;?>" style="transform: scale(1.5);"></td>
                <td style="text-align:left;"><?=$row->order_id;?></td>
                <td><?=$row->full_name;?></td>
                <td style="text-align:right;"><?=number_format($row->total_price,2);?></td>
                <td><?=$row->order_status;?></td>
                <td style="text-align:center;">
                    <?php
                        if($row->order_status == 'Active'){
                            echo "<span class=\"badge badge-success\">".$row->order_status."</span></td>";
                        }else{
                            echo "<span class=\"badge badge-danger\">".$row->order_status."</span></td>";
                        }
                    ?>
                    
                <td><?=$row->date_create;?></td>
                <td style="text-align:right;">
                    <!-- ปุ่มดำเนินการ -->
                    <a href="<?=base_url('Followups?cid=').$row->order_id;?>" class="btn btn-info btn-sm"><span class="fas fa-phone"></span> เปิดงาน</a>
                    <button class="btn btn-warning btn-sm" onclick="FcEditCustomerModalByCID('edit','<?=$row->order_id;?>');"><span class="fas fa-edit"></span> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="FcDelCustomerByCID('<?=$row->order_id;?>');"><span class="fas fa-trash"></span> ลบ</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/  
    $('#SaleOrderTable').DataTable({
        "pageLength": 25, // กำหนดให้แสดง 50 รายการเป็นค่าเริ่มต้น  
        "order": [[1, "desc"]], // เรียงลำดับคอลัมน์แรก (order_id) จากมากไปน้อย      
        "language": {
            "search": "ค้นหา:",
            "lengthMenu": "แสดง _MENU_ รายการ/หน้า",
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