<!-- Chart.js -->
<script src="<?=base_url('assets/plugins/chartjs/Chart.bundle.min.js');?>"></script>
<link rel="stylesheet" href="<?=base_url('assets/custom/css/reports.css');?>">

<div class="chart-wrapper">
    <h4 class="text-center">Call Summary Report</h4>
    <div class="chart-container">
        <canvas id="callPieChart"></canvas>
    </div>
</div>

<hr>

<div class="report-wrapper mt-4">
    <h3 class="text-center" style="font-weight: bold;">สรุปรายการโทร (Call Details)</h3>
    <h5 class="text-center">รายงานลูกค้า - จากวันที่ <?=$condition['from_date'];?> ถึงวันที่ <?=$condition['to_date'];?></h5>
    <table class="table table-bordered table-striped" id="callTable">
        <thead class="thead-light">
            <tr>
                <th>ลำดับ</th>
                <th>รหัสลูกค้า</th>
                <th class="text-left">ชื่อ-นามสกุล</th>
                <th class="text-left">รหัสอ้างอิง</th>
                <th class="text-left">เบอร์โทร</th>
                <th class="text-left">วันที่่โทร</th>
                <th class="text-left">ผลการโทร</th>
                <th class="text-left">แจ้งผลผ่าน</th>
                <th class="text-left">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1;
                foreach($ReportTable as $row):
            ?>
                <tr>
                    <td><?=$i++;?></td>
                    <td><?=$row->customer_id;?></td>
                    <td><?=$row->full_name;?></td>
                    <td><?=$row->ref_user_id;?></td>
                    <td><?=$row->phone_number;?></td>
                    <td><?=$row->call_datetime;?></td>
                    <td><?=$row->call_result;?></td>
                    <td><?=$row->notified_via_line;?></td>
                    <td><?=$row->cstatus;?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Convert the PHP array from the controller into a JavaScript object
    var chartData = <?= json_encode($ReportChartSummary); ?>;
    
    // Prepare labels and data arrays for the chart
    var labels = [];
    var data = [];
    
    chartData.forEach(function(item) {
        labels.push(item.call_result);
        data.push(item.cnt);
    });
    
    // Create the Pie Chart
    var ctx = document.getElementById('callPieChart').getContext('2d');
    var callPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#28a745',    // Completed
                    '#ffe033',    // In Progress
                    '#fd7e14',    // Postponed
                    '#dc3545'     // Failed
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = (value / total * 100).toFixed(1);
                            return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
