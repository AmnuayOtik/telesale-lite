
<table class="table table-striped table-valign-middle" id="CustomerTable">
    <thead>
        <tr>
            <th>#</th>
            <th style="width:10%;text-align:left;white-space=nowrap;">เลขรายการ</th>
            <th style="width:15%;white-space=nowrap;">ชื่อลูกค้า</th>
            <th style="width:15%;white-space=nowrap;">อ้างอิงรหัสลูกค้า</th>            
            <th style="width:10%;white-space=nowrap;">บัญชีไลน์</th>
            <th style="width:10%;white-space=nowrap;">วันที่โทร</th>
            <th style="width:10%;white-space=nowrap;text-align:center;">ครั้งที่โทร</th>
            <th style="width:10%;text-align:center;white-space=nowrap;">สถานะ</th>            
            <th style="width:20%;text-align:right;white-space=nowrap;">ดำเนินการ</th>
        </tr>
    </thead>
    <tbody id="CustomerTableBody">

        <?php foreach($customers as $row){ ?>                                 
            <tr>
                <td><input type="checkbox" name="customer[]" id="<?=$row->customer_id;?>" value="<?=$row->customer_id;?>" style="transform: scale(1.5);"></td>
                <td style="text-align:left;"><?=$row->customer_id;?></td>
                <td><?=$row->full_name;?></td>
                <td><?=$row->ref_user_id;?></td>                
                <td><?=$row->line_account;?></td>
                <td style="white-space:nowrap;"><?=$row->call_datetime;?></td>
                <td style="text-align:center;"><?=$row->call_count;?></td>
                <td style="text-align:center;">
                    <?php
                        $status = $row->cstatus;

                        switch ($status) {
                            case 'Waiting':
                                $badge_class = 'badge-warning'; // สีเหลือง
                                break;
                            case 'Finished':
                                $badge_class = 'badge-success'; // สีเขียว
                                break;
                            case 'Postpone':
                                $badge_class = 'badge-secondary'; // สีเทา
                                break;
                            case 'Incomplete':
                                $badge_class = 'badge-danger'; // สีแดง
                                break;
                            default:
                                $badge_class = 'badge-light'; // สีพื้นฐาน กรณีไม่รู้จักสถานะ
                        }

                        echo "<span class=\"badge $badge_class\">$status</span>";
                    ?>
  
                </td>           
                <td style="text-align:right;white-space:nowrap;">
                    <!-- ปุ่มดำเนินการ -->
                    <a href="<?=base_url('Followups?cid=').$row->customer_id;?>" class="btn btn-info btn-sm"><span class="far fa-file-audio"></span> เปิดงาน</a>
                    <button class="btn btn-warning btn-sm" onclick="FcEditCustomerModalByCID('edit','<?=$row->customer_id;?>');"><span class="fas fa-edit"></span> แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="FcDelCustomerByCID('<?=$row->customer_id;?>');"><span class="fas fa-trash"></span> ลบ</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    /*********************************************************
    * ฟังก์ชั่นลบรายการกลุ่มออก
    *********************************************************/  
    $('#CustomerTable').DataTable({
        "pageLength": 25, // กำหนดให้แสดง 50 รายการเป็นค่าเริ่มต้น  
        
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