<!-- Chart.js -->
<script src="<?=base_url('assets/plugins/chartjs/Chart.bundle.min.js');?>"></script>

<style>
    body, html {
        height: 100%;
        margin: 0;
    }

    .chart-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ffffff;
        padding: 20px;
        flex-direction: column;
    }

    .chart-container {
        width: 100%;
        max-width: 700px;
    }

    .report-wrapper {
        text-align: left;
    }

    h4 {
        margin-bottom: 30px;
        color: #333;
    }
</style>

<div class="chart-wrapper">
    <h4 class="text-center">Call Summary Report</h4>
    <div class="chart-container">
        <canvas id="callPieChart"></canvas>
    </div>
</div>

<hr>

<div class="report-wrapper mt-4">
    <h3 class="text-center" style="font-weight: bold;">สรุปรายการโทร (Call Details)</h3>
    <h5>รายงานสรุปยอดการโทร - จากวันที่ <?=$condition['from_date'];?> ถึงวันที่ <?=$condition['to_date'];?></h5>
    <table class="table table-bordered table-striped" id="callTable">
        <thead class="thead-light">
            <tr>
                <th>ลำดับ</th>
                <th>พนักงานโทร</th>
                <th class="text-right">รอดำเนินการ</th>
                <th class="text-right">ติดต่อลูกค้าไม่ได้</th>
                <th class="text-right">ขอเลื่อน</th>
                <th class="text-right">เสร็จสิ้น</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1;
                $Waiting = 0;
                $Incomplete = 0;
                $Postpone = 0;
                $Finished = 0;

                foreach($ReportTable as $row){
                    $Waiting    += $row->Waiting;
                    $Incomplete += $row->Incomplete;
                    $Postpone   += $row->Postpone;
                    $Finished   += $row->Finished;
            ?>
                <tr>
                    <td><?=$i++;?></td>
                    <td><?=$row->full_name;?></td>
                    <td class="text-right"><?=number_format($row->Waiting);?></td>
                    <td class="text-right"><?=number_format($row->Incomplete);?></td>
                    <td class="text-right"><?=number_format($row->Postpone);?></td>
                    <td class="text-right"><?=number_format($row->Finished);?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="2" class="text-right">รวมทั้งหมด</td>
                <td class="text-right"><?=number_format($Waiting);?></td>
                <td class="text-right"><?=number_format($Incomplete);?></td>
                <td class="text-right"><?=number_format($Postpone);?></td>
                <td class="text-right"><?=number_format($Finished);?></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    var chartData = <?= json_encode($ReportChartSummary); ?>;

    if (typeof callPieChart !== 'undefined') {
        callPieChart.destroy();
    }

    var ctx = document.getElementById('callPieChart').getContext('2d');

    var callPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['เสร็จสิ้น', 'รอดำเนินการ', 'ขอเลื่อน', 'ติดต่อลูกค้าไม่ได้'],
            datasets: [{
                data: [
                    chartData.Finished || 0,
                    chartData.Waiting || 0,
                    chartData.Postpone || 0,
                    chartData.Incomplete || 0
                ],
                backgroundColor: [
                    '#28a745',    // เสร็จสิ้น
                    '#ffe033',    // รอดำเนินการ
                    '#fd7e14',    // ขอเลื่อน
                    '#dc3545'     // ติดต่อลูกค้าไม่ได้
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

