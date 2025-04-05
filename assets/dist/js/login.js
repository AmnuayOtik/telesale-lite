
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
            url		: "CheckUserLogin",
            data	: $('#frmLogin').serialize(),
            cache	: false,
            timeout	: 0,
            success	: function (tResult) {                               
                var aReturn = JSON.parse(tResult);
                if(aReturn.rtCode == '1'){
                    window.location.href = url; 
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
