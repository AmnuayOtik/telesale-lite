<!-- Modal Change Password -->
<div class="modal fade" id="FilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
        
        <div class="modal-header" style="border-color: bisque;background-color: antiquewhite;">
            <h4 class="modal-title">กรองช่วงเวลาที่ต้องการค้นหา : <span id="result_user"></span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="false">&times;</span></button>
        </div>
        
        <div class="modal-body">

            <form method="POST" name="frmSearch" id="frmSearch">
                <div class="mb-12">                                
                    <label for="date_filter" class="form-label">เลือกช่วงเวลาที่ต้องการกรอง:</label>
                    
                    <?php 
                        $selected = $this->session->userdata('date_filter'); 
                        $is_admin = $this->session->userdata('is_admin');
                    ?>

                    <select name="date_filter" class="form-control" required>
                        <option value="">-- เลือกช่วงเวลา --</option>
                        <option value="today" <?= ($selected == 'today') ? 'selected' : '' ?>>วันนี้</option>
                        <?php if ($is_admin): ?>
                            <option value="yesterday" <?= ($selected == 'yesterday') ? 'selected' : '' ?>>เมื่อวาน</option>
                            <option value="this_week" <?= ($selected == 'this_week') ? 'selected' : '' ?>>สัปดาห์นี้</option>
                            <option value="this_month" <?= ($selected == 'this_month') ? 'selected' : '' ?>>เดือนนี้</option>
                            <option value="this_year" <?= ($selected == 'this_year') ? 'selected' : '' ?>>ปีนี้</option>
                            
                        <?php endif; ?>

                        <option value="date_range" <?= ($selected == 'date_range') ? 'selected' : '' ?>>กำหนดเอง</option>

                    </select>



                </div>       
                <?php

                    if (empty($this->session->userdata('from_date'))) {
                        $from_date = date('Y-m-01'); // วันที่ 1 ของเดือนปัจจุบัน
                    } else {
                        $from_date = $this->session->userdata('from_date');
                    }

                    if (empty($this->session->userdata('to_date'))) {
                        $to_date = date('Y-m-d'); // วันที่ปัจจุบัน
                    } else {
                        $to_date = $this->session->userdata('to_date');
                    }


                ?>
                <div id="custom_date_range" style="display: none; margin-top: 10px;">
                    <label for="from_date">จากวันที่:</label>
                    <input type="date" id="from_date" name="from_date" value="<?=$from_date;?>" class="form-control">

                    <label for="to_date" style="margin-top: 10px;">ถึงวันที่:</label>
                    <input type="date" id="to_date" name="to_date"  value="<?=$to_date;?>" class="form-control">
                </div>

            </form>


        </div>
        
        <div class="modal-footer justify-content-between">                            
            <button type="button" class="btn btn-default" data-dismiss="modal"> <span class="fas fa-times-circle"></span> ปิด</button>
            <button type="button" class="btn btn-success" id="Search" onclick="Search();"><span class="fas fa-save"></span> ค้นหา</button>
        </div>
        
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        
        function toggleDateRange() {
            if ($('select[name="date_filter"]').val() === 'date_range') {
                $('#custom_date_range').slideDown();
            } else {
                $('#custom_date_range').slideUp();
                /*
                $('#from_date').val('');
                $('#to_date').val('');
                */
            }
        }

        // เรียกใช้เมื่อโหลดหน้าครั้งแรก (สำหรับกรณี selected ไว้แล้ว)
        toggleDateRange();

        // เรียกใช้เมื่อมีการเปลี่ยนค่า
        $('select[name="date_filter"]').change(function () {
            toggleDateRange();
        });
    });
</script>