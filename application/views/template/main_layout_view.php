<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Header -->
<?php $this->load->view('template/header_view'); ?>
<!-- Navbar -->
<?php $this->load->view('template/menu_navbar_view'); ?>
<!-- Content -->
<?php if(isset($content)) $this->load->view($content); ?>
<!-- Footer -->
<?php $this->load->view('template/footer_view'); ?>