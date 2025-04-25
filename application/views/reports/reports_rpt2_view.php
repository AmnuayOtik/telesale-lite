<?php
$rFrom_date = $this->session->userdata('rFrom_date');
$rTo_date   = $this->session->userdata('rTo_date');

?>
<div class="modal fade" id="ReportsformModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: beige;">
                <h4 class="modal-title">กำหนดเงื่อนไขรายการ | Condition</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="false">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height:765px; overflow-y: auto;">                            
                <div class="card-body" style="padding: 14px;">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                            <form name="frmReports" action="Reports/Report2" method="post" id="frmReports">
                                <!-- Customer ID (Auto generated) -->
                                <div class="form-group">
                                    <label for="from_date">จากวันที่</label>                                    
                                    <input type="text" class="form-control" style="border-color: #f5b6b6;" id="from_date" name="from_date"  data-provide="datepicker" data-date-language="th" value="<?=$rFrom_date;?>" placeholder="เลือกวันที่ วัน-เดือน-ปี">
                                </div>
                                <!-- Customer ID (Auto generated) -->
                                <div class="form-group">
                                    <label for="to_date">ถึงวันที่</label>                                    
                                    <input type="text" class="form-control" style="border-color: #f5b6b6;" id="to_date" name="to_date" data-provide="datepicker" data-date-language="th" maxlength="10" value="<?=$rTo_date;?>" placeholder="เลือกวันที่ วัน-เดือน-ปี">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <span class="fas fa-times-circle"></span> ปิด</button>
                <button type="button" class="btn btn-success" id="cmdPrint"><span class="fas fa-print"></span> แสดง</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        console.log("jQuery version: " + $.fn.jquery);
        /*********************************************************
        * กำหนด datepicker ให้กับ from_date และ to_date
        *********************************************************/
        $('#from_date, #to_date').datepicker({
            format: 'dd-mm-yyyy',
            language: 'th',
            autoclose: true,
            todayHighlight: true
        });

        /*********************************************************
        * ตรวจสอบเมื่อมีการเปลี่ยนวันที่
        *********************************************************/        
        $('#to_date').on('change', function () {
            const fromDateStr = $('#from_date').val();
            const toDateStr = $('#to_date').val();

            if (fromDateStr && toDateStr) {
                // แปลงวันที่จาก dd-mm-yyyy เป็น Date object
                const fromParts = fromDateStr.split('-');
                const toParts = toDateStr.split('-');

                const fromDate = new Date(fromParts[2], fromParts[1] - 1, fromParts[0]);
                const toDate = new Date(toParts[2], toParts[1] - 1, toParts[0]);

                if (toDate < fromDate) {
                    toastr.error('วันที่สิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้น');
                    $('#to_date').val(''); // เคลียร์ค่า
                }
            }
        });

        /*********************************************************
        * คลิกเพื่อเปิดรายงานที่ 1
        *********************************************************/
        $('#cmdPrint').click(function () {
            const fromDateStr = $('#from_date').val();
            const toDateStr = $('#to_date').val();

            if (!fromDateStr || !toDateStr) {
                toastr.warning('กรุณาเลือกวันที่ให้ครบถ้วน');
                return;
            }
            $('#Report-Graph').html();

            $.ajax({
                url: 'Reports/Report2',
                method: 'POST',
                data: {
                    from_date: fromDateStr,
                    to_date: toDateStr
                },
                success: function (response) {                    
                    $('#ReportsformModal').modal('hide');
                    $('#ReportsTableBody').slideToggle();
                    $('#Report-main-result').css('display', 'block');
                    $('#Report-Graph').html(response);
                },
                error: function () {
                    $('#Report-Graph').html('<p class="text-danger">เกิดข้อผิดพลาดในการโหลดกราฟ</p>');
                }
            });
        });

    });
</script>
