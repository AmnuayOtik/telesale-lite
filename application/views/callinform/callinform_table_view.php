
<table class="table table-striped table-valign-middle" id="CallInformTable">
    <thead>
        <tr>            
            <th style="width:10%;text-align:center;white-space=nowrap;">เลขรายการ</th>
            <th style="text-algin:left;white-space=nowrap;">ชื่อรายการ</th>          
            <th style="width:20%;text-align:right;white-space=nowrap;">ดำเนินการ</th>
        </tr>
    </thead>
    <tbody id="CallInformTableBody">

        <?php 
            
            foreach($callinform as $row){ 
        ?>                                 
            <tr>
                <td style="text-align:center;"><?=$row->id;?></td>
                <td style="text-align:left;"><?=$row->name_th;?></td>                
                <td style="text-align:right;white-space:nowrap;">
                    <!-- ปุ่มดำเนินการ -->                    
                    <button class="btn btn-warning btn-sm" onclick="FcEditCallInformModalByID('edit','<?=$row->id;?>');"><span class="fas fa-edit"></span> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="FcDelCallInformByID('<?=$row->id;?>');"><span class="fas fa-trash"></span> ลบ</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/  
    $('#CallInformTable').DataTable({
        "pageLength": 10, // กำหนดให้แสดง 50 รายการเป็นค่าเริ่มต้น  
        
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