<div class="modal fade" id="CustomerCsvImportModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: beige;">
                <h4 class="modal-title">นำเข้าข้อมูลลูกค้า | Customer Import Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="false">&times;</span>
                </button>
            </div>
        <div class="modal-body" style="max-height:765px; overflow-y: auto;">                            
            
                <div class="card-body" style="padding: 14px;">
                    
                    <div class="card card-primary card-outline card-outline-tabs">
                        
                        <div class="card-header p-0 border-bottom-0">                                    
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item" id="cmdOne">
                        
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false"><span class="fas fa-user"></span> นำเข้าข้อมูลใหม่</a>
                                </li>                                       
                            </ul>
                        </div>
                        
                        <div class="card-body" id="FormCustomer">
                            
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

                                    <form id="csvImportForm" enctype="multipart/form-data">

                                         <!-- Csv_file -->
                                         <div class="form-group">
                                            <label for="user_id">เลือกพนักงานผู้รับผิดชอบ (Assign to)</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                <?php 
                                                    foreach($users as $row){
                                                        echo "<option value=\"".$row->user_id."\">".$row->full_name."</option>";
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>

                                        <!-- Csv_file -->
                                        <div class="form-group">
                                            <label for="csv_file">เลือกไฟล์ CSV ( <a href="<?=base_url('assets/csv_template/customers_sample.csv');?>">ดาวน์โหลดไฟล์ ตัวอย่าง</a> )</label>
                                            <input type="file" class="form-control" style="border-color: #f5b6b6;" id="csv_file" name="csv_file" accept=".csv" placeholder="เลือกไฟล์ csv" required>
                                        </div>

                                        <div id="progress"></div>

                                    </form>
                                </div>
                            </div>

                        </div>

                    <!-- /.card -->
                    </div>
                    
                </div>
                <!-- /.card-body -->

        </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <span class="fas fa-times-circle"></span> ปิด</button>
                <button type="button" class="btn btn-primary" id="cmdCsvImport" onclick="fcCustomerCsvImport();"><span class="fas fa-save"></span> นำเข้า</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>

    $(document).ready(function () {

        /*********************************************************
        * ฟังก์ชั่นตรวจสอบไฟล์ รับเฉพาะ .csv เท่านั้น
        *********************************************************/  
        $("#csv_file").change(function () {
            var file = this.files[0];
            if (file) {
                var fileType = file.name.split('.').pop().toLowerCase();
                if (fileType !== "csv") {                    
                    toastr.warning("กรุณาเลือกไฟล์ที่เป็น .csv เท่านั้น!","แจ้งเตือน")
                    $(this).val(""); // ล้างค่า input
                }
            }
        });

    });

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มใหม่
    *********************************************************/  
    function fcCustomerCsvImport() {
        const csvFile = document.getElementById("csv_file").files[0];

        if(csvFile == null){
            toastr.error("กรุณาเลือกไฟล์ที่ต้องการนำเข้า","แจ้งเตือน");
            return;
        }

        const form = document.getElementById("csvImportForm"); // เลือกฟอร์ม
        const formData = new FormData(form);

        $.ajax({
            url: "<?= base_url('Customer/Upload_csv') ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                importData(response.file_path);
            }
        });
    }

    /*********************************************************
    * ฟังก์ชั่นเปิดฟอร์มใหม่
    *********************************************************/  
    function importData(filePath = null) {
        if (!filePath) {
            Swal.fire("เกิดข้อผิดพลาด!", "ไม่พบไฟล์ CSV", "error");
            return;
        }

        // แสดง SweetAlert Spinner ขณะรอ response
        Swal.fire({
            title: "กำลังนำเข้าข้อมูล...",
            text: "โปรดรอสักครู่",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "<?= base_url('Customer/Import_csv') ?>",
            type: "POST",
            data: { file_name: filePath },
            success: function (response) {
                Swal.close(); // ปิด Spinner เมื่อได้รับ response
                if(response.rCode == 200 && response.rMsg == 'Success'){
                    $("#progress").html(response.rData);
                }else{
                    $("#progress").html(response.rData);
                }
            },
            error: function () {
                Swal.close(); // ปิด Spinner กรณีเกิดข้อผิดพลาด
                Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถนำเข้าข้อมูลได้", "error");
            }
        });
    }

    $('#CustomerCsvImportModal').on('hidden.bs.modal', function () {
        console.log('Modal ถูกปิดแล้ว!');
        // ทำอย่างอื่นได้ที่นี่ เช่น รีเซ็ตฟอร์ม
        fcReloadCustomerTable();
    });


</script>