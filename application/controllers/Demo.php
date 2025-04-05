<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller{

    public function index(){
        $this->load->library('hello');

        $message = $this->hello->world();

        echo $message;
    }
    

}