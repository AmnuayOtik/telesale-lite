<table class="table table-striped table-valign-middle" id="UsersTable">
    <thead>
        <tr>
            <th>#</th>
            <th style="text-align:left;">รหัสผู้ใช้</th>
            <th style="width:10%;">ชื่อบัญชี</th>
            <th style="width:20%;">ชื่อ-นามสกุล</th>
            <th>อีเมล</th>
            <th style="width:15%;white-space:nowrap;">เบอร์โทรศัพท์</th>
            <th style="white-space:nowrap;width:15%;text-align:center;">ประเภทผู้ใช้</th>
            <th style="text-align:center;">สถานะ</th>
            <th style="width:20%;text-align:right;">ดำเนินการ</th>
        </tr>
    </thead>
    <tbody id="UsersTableBody">

        <?php foreach($users as $row){ ?>                                 
            <tr>
                <td><input type="checkbox" name="user_id[]" id="<?=$row->user_id;?>" value="<?=$row->user_id;?>" style="transform: scale(1.5);"></td>
                <td style="text-align:left;white-space:nowrap;"><?=$row->user_id;?></td>
                <td><?=$row->username;?></td>
                <td><?=$row->full_name;?></td>
                <td><?=$row->email;?></td>
                <td><?=$row->mobile_phone;?></td>
                <td style="white-space:nowrap;text-align:center;">
                    <?php
                        if($row->user_type=='1'){
                            echo "<span class=\"badge badge-success\" style=\"padding:10px;\">ผู้ดูแลระบบ<span>";
                        }else{
                            echo "<span class=\"badge badge-warning\" style=\"padding:10px;\">ผู้ใชัทั่วไป<span>";
                        }
                    ?>
                </td>
                <td style="text-align:center;">
                    <?php
                        if($row->cstatus == '1'){
                            echo "<span class=\"badge badge-success\" style=\"padding:10px;\"><span class=\"fas fa-unlock\"></span></span></td>";
                        }else{
                            echo "<span class=\"badge badge-danger\" style=\"padding:10px;\"><span class=\"fas fa-lock\"></span></span></td>";
                        }
                    ?>
                <td style="text-align:right;white-space:nowrap;">
                    <!-- ปุ่มดำเนินการ -->
                    <button class="btn btn-default btn-sm" onclick="FcChangeUsersPassWdByUID('<?=$row->user_id;?>');"><span class="fas fa-key"></span> รหัสผ่าน</button>
                    <button class="btn btn-warning btn-sm" onclick="FcEditUsersModalByUID('edit','<?=$row->user_id;?>');"><span class="fas fa-edit"></span> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="FcDelUsersByUID('<?=$row->user_id;?>');"><span class="fas fa-trash"></span> ลบ</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>

    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/  
    $('#UsersTable').DataTable({
        "pageLength": 25, // กำหนดให้แสดง 50 รายการเป็นค่าเริ่มต้น  
        "order": [[1, "desc"]], // เรียงลำดับคอลัมน์แรก (customer_id) จากมากไปน้อย      
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