<?php
class ErrorHandler extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->helper("url");
    $this->load->library("session");
  }

  public function index() {
    $this->load->view("errorHandler");
  }
}
