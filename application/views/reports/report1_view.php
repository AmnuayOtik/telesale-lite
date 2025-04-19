
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานรายการเอกสารที่ขอนำเข้า / ส่งออก - ตามช่วงเวลาที่กำหนด</title>
    <link rel="icon" type="image/x-icon" href="<?=base_url('assets/dist/img/logo.ico');?>">

    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url('../../fonts/thsarabunnew_italic-webfont.eot');
            src: url('../../fonts/thsarabunnew_italic-webfont.eot?#iefix') format('embedded-opentype'),
                url('../../fonts/thsarabunnew_italic-webfont.woff') format('woff'),
                url('../../fonts/thsarabunnew_italic-webfont.ttf') format('truetype');
            font-weight: normal;
            font-style: italic;

        }

        @font-face {
            font-family: 'THSarabunNew';
            src: url('../../fonts/thsarabunnew_bold-webfont.eot');
            src: url('../../fonts/thsarabunnew_bold-webfont.eot?#iefix') format('embedded-opentype'),
                url('../../fonts/thsarabunnew_bold-webfont.woff') format('woff'),
                url('../../fonts/thsarabunnew_bold-webfont.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;

        }
        @page {
            margin-left: 5mm;
            margin-right: 5mm;
            margin-top: 2mm;
            margin-bottom: 5mm;
        }
        body{
            margin-top:5px !important;
            margin-left: 10px !important;
            margin-bottom: 0px !important;
            padding: 12px !important;            
            font-family: "THSarabunNew", "Garuda", "Tahoma", sans-serif;	
            font-size: 22px;
		}
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h3 style="text-align:center;">รายงานรายการเอกสารที่ขอนำเข้า / ส่งออก - ตามช่วงเวลาที่กำหนด</h3>
    <span style="text-align:center;">เงื่อนไขรายงาน จากวันที่ <?=$condition['from_date'];?> ถึงวันที่ <?=$condition['to_date'];?></span>

    <hr>
    <table>
        <thead>
            <tr>
                <th>ชื่อบุคคล/นิติบุคคล</th>
                <th style="text-align:center;">นำเข้า</th>
                <th style="text-align:center;">ส่งออก</th>
                <th style="text-align:center;">แปรสภาพ</th>                 
            </tr>
        </thead>
        <tbody>
            <?php

                if(!empty($report1)){

                foreach($report1 as $row){
            ?>
            <tr>                
                <td style="vertical-align: top;text-align:left;"><?=$row['USER_NAME'];?></td> 
                <td style="vertical-align: top;text-align:center;"><?=$row['IMPORT_COUNT'];?></td>                                               
                <td style="vertical-align: top;text-align:center;"><?=$row['EXPORT_COUNT'];?></td>                
                <td style="vertical-align: top;text-align:center;"><?=$row['CONVERT_COUNT'];?></td>                
            </tr>
            <?php 
                } 
            }else{
                echo "empty data";
            }
            ?>
            
        </tbody>
    </table>
    
</body>
</html>