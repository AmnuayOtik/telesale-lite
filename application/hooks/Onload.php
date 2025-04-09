<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// จะทำงานก่อนเข้า controler

function check_login()
{
    
    $CI =& get_instance();
    $controller = $CI->router->class;
  
    if(empty($CI->session->userdata("user_id"))){
    
      if($controller!="Welcome" && $controller!="welcome" && $controller !="Login" && $controller != "login" && $controller!="WebServices"){
        redirect(base_url(), 'refresh');
      }
		
    }

}
