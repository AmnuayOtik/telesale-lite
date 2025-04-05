<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TeleSale System | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url("assets/plugins/fontawesome-free/css/all.min.css"); ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url("assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css"); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url("assets/dist/css/adminlte.min.css"); ?>">
  <link rel="stylesheet" href="<?=base_url("assets/dist/css/style.css"); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200&display=swap" rel="stylesheet">
<style>
  body{
    font-family: 'Kanit', sans-serif;
    background-image: url("assets/images/bg.jpeg");
    /* Full height */
    height: 100%;

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }  
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  


  <div class="card">
    <div class="card-body login-card-body">
    
    <div class="login-logo">
      <img src="<?=base_url('assets/images/logo.png');?>" style="width:110px;">
      <br>
      <a href=""><b>TeleSale</b> System</a>
      <hr/>
    </div>
  <!-- /.login-logo -->

      <p class="login-box-msg">ระบบบริหารงานขาย| กรุณาเข้าสู่ระบบ</p>

      <form id="frmLogin" class="form-signin" name="frmLogin" method="post">      
        <div class="input-group mb-3">        
          <input type="text" id="username" name="username" class="form-control" placeholder="ชื่อบัญชี" autocomplete="new-user">
          <input type="hidden" name="url" id="url" value="<?=base_url('Dashboard');?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          
        </div>
        <label id="UserMsg" style="color: red; display: none">*กรุณากรอกชื่อผู้ใช้ให้ถูกต้อง</label>
        <div class="input-group mb-3">
          <input type="password" id="password" name="password" class="form-control" placeholder="รหัสผ่าน" autocomplete="new-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <label id="PwdMsg" style="color: red; display: none">*กรอกรหัสผ่านให้ถูกต้อง</label>
        

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                จดจำรหัสผ่านของท่าน
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="button" name="cmdLogin" id="cmdLogin" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>                        
          </div>
          <!-- /.col -->
        </div>
        
        <div class="row center" id="loading" style="display:none">
            <img src="<?=base_url('assets/images/load.gif');?>" style="width: 190px;">
            
        </div>
        <div id="showError" style="color:red;"></div>
        
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url("assets/plugins/jquery/jquery.min.js"); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url("assets/plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
<!-- App -->
<script src="<?=base_url("assets/dist/js/adminlte.min.js"); ?>"></script>

<script>
    
$(document).ready(function(e){
    console.log("Script Ready.");
});

/************************************************
 ตรวจสอบการเข้าสู่ระบบ | Check Login
*************************************************/

$('#cmdLogin').click(function(){

    let username = $.trim($("#username").val());
    let password = $.trim($("#password").val());
    let url = $.trim($("#url").val());

    if(username == '' || password == ''){
        $("#UserMsg").css('display','block');
        $("#PwdMsg").css('display','block');
        return false;
    }else{
        $("#UserMsg").css('display','none');
        $("#PwdMsg").css('display','none');
    }

    $('#loading').css('display','block');
    $("#username").prop("readonly", true);
    $("#password").prop("readonly", true);
    $("#cmdLogin").prop("disabled", true);
    
    setTimeout(() => {        
        $.ajax({
            type	: "POST",
            url		: "Login/FcCheckLogin",
            data	: $('#frmLogin').serialize(),
            cache	: false,
            timeout	: 0,
            success	: function (response) {
                if(response.rCode == 200 && response.rMsg == 'Success'){
                    window.location.href = 'Dashboard'; 
                }else{
                    $('#loading').css('display','none');                                        
                    $("#username").removeAttr("readonly");
                    $("#password").removeAttr("readonly");
                    $("#cmdLogin").removeAttr("disabled");
                    $("#showError").html("*ข้อมูลไม่ถูกต้อง กรุณาลองใหม่*");
                }                  
            },
            error: function (jqXHR, textStatus, errorThrown) {               
                $('#loading').css('display','none');                                        
                $("#username").removeAttr("readonly");
                $("#password").removeAttr("readonly");
                $("#cmdLogin").removeAttr("disabled");
                $("#showError").html("*Error! Contact Administrator*");
            }
        });
    }, 1000);
});

</script>


</body>
</html>
