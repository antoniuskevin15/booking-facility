<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('grocery_crud_model');
    $this->load->model('manage');
    $this->load->model('Auth');
    $this->load->helper('cookie');
  }

  public function index(){
    redirect('user/facilities');
  }

  public function facilities(){
    $this->Auth->validateUserRole();
    $data['role'] = 'user';
    $this->load->library('grocery_CRUD');
    $crud = new grocery_CRUD();
    $crud->set_table('facility');
    $crud->set_theme('tablestrap');

    $output = $crud->render();
    $data['title'] = 'Booking Facility Website — Facility Listing';
    $data['crud'] = get_object_vars($output);
    $data['facility'] = $this->manage->printAllFacility();
    $this->template->load('template/template_navbar', 'pages/UserFacilityList', $data);
  }

  public function requests(){
    // $arr = $this->manage->getBookingInfo();
    // $result = $arr[0]['']
    // var_dump($this->input->get('FID'));
    // die;
    if(isset($_SESSION['after_insert'])) {      
      $config = [
        "Date" => implode("-", array_reverse(explode("/", $_SESSION['after_insert']['Date']))),
        "RequestID" => null,
        "RequesterID" => $_SESSION['after_insert']['RequesterID'],
        "ReqFacilityID" => $_SESSION['after_insert']['ReqFacilityID'],
        "StartTime" => $_SESSION['after_insert']['StartTime'],
        "EndTime" => $_SESSION['after_insert']['EndTime']
      ];

      $this->db->insert("requests", $config);

      unset($_SESSION['after_insert']);
    }
    $this->Auth->validateUserRole();
    $data['role'] = 'user';
    $this->load->library('grocery_CRUD');
    $crud = new grocery_CRUD();
    $crud->where(['reserveduser.RequesterID' => $_SESSION['account']['UserID']]);
    $crud->set_theme('tablestrap');
    $crud->set_table('reserveduser');
    $crud->set_subject('Request');
    $crud->columns('RequestID','ReqFacilityID','Date','StartTime','EndTime','Status');
    $crud->display_as('ReqFacilityID','Requested Facility');
    $crud->callback_add_field('RequesterID',array($this, 'insert_requester'));
    $crud->callback_add_field('Status',array($this, 'insert_status'));
    $crud->callback_add_field('ReqFacilityID',array($this, 'insert_facilityName'));
    $crud->callback_add_field('StartTime',array($this, 'insert_StartTime'));
    $crud->callback_add_field('EndTime',array($this, 'insert_EndTime'));
    $crud->set_rules('Date','Date','callback_validationDate');
    $crud->set_rules('StartTime','Start Time','callback_validationTime');
    // $crud->set_rules('EndTime','End Time','callback_bookingAvailable');
    // $crud->callback_before_insert(array($this, "validationDate"));
    // $crud->callback_before_insert(array($this, "validationTime"));
    $crud->unset_edit();
    $crud->unset_delete();
    $crud->unset_print();
    $crud->unset_export();
    $crud->unset_read();

    $output = $crud->render();
    $data['crud'] = get_object_vars($output);
    $data['title'] = 'Booking Facility Website — Request Listing';
    $this->template->load('template/template_navbar', 'pages/RequestListing', $data);
  }

  // public function validationDate($post_array, $primary_key) {
  //   return (strtotime($post_array['Date']) < strtotime('now')) ? false : true ;
  // }

  // public function bookingAvailable($EndTime){
  //   // $this->load->database();
  //   $id = $this->input->get('FID');
  //   $date = implode("-", explode("/", $_POST['Date']));
  //   // $query = $this->db->query("SELECT StartTime, EndTime, `Date`, ReqFacilityID FROM reserveduser WHERE ReqFacilityID = $id");
  //   // $bookedFacility = $query->result_array();
  //   $bookedFacility = $this->manage->getBookingInfo($id, $date);
  //   foreach($bookedFacility as $each) {
  //       // if ($_POST['Date'] == $each['Date'])
  //       //     {
  //             if($EndTime > $each['StartTime'] &&
  //             $EndTime < $each['EndTime'] ){
  //                  $this->form_validation->set_message("bookingAvailable", "Maaf, waktu pada jam tersebut sudah di booking");
  //                  return false;
  //                }
  //                else if($_POST['StartTime'] > $each["StartTime"] &&
  //                $_POST['StartTime'] < $each['EndTime']){
  //                         $this->form_validation->set_message("bookingAvailable", "Maaf, waktu pada jam tersebut sudah di booking");
  //                         return false;
  //                       }
  //           // }
  //         }
  //         return true;
  // }

  public function validationTime($StartTime) {
    if($StartTime > $_POST['EndTime']) {
      $this->form_validation->set_message("validationTime", "Start Time harus lebih awal daripada End Time!");
      return false;
    }

    // $bookedFacility = $this->manage->getBookingInfo();
    
    // foreach($bookedFacility as $each) {
    //   if ($StartTime > $each['StartTime'] &&
    //       $StartTime < $each['EndTime'] &&
    //       $this->input->get('FID') == $each['FacilityID'] &&
    //       $_POST['Date'] == $each['Date']) return false;
      
    //   else if ($_POST['EndTime'] > $each["StartTime"] &&
    //           $_POST['EndTime'] < $each['EndTime'] &&
    //           $this->input->get('FID') == $each['FacilityID'] &&
    //           $each['Date'] == $_POST['Date']) return false;
      
      // if($each['Date'] == $_POST['Date'] && $each['ReqFacilityID'] == $this->input->get('FID') && $StartTime > $each['StartTime'] && $_POST['EndTime'] < $each['EndTime']) {
      //   $this->form_validation->set_message("validationTime", "Maaf, waktu pada jam tersebut sudah di booking oleh {$each['Username']}");
      //   return false;
      // }
    // }

    return true;
  }

  public function validationDate() {
    if(strtotime(implode("-", explode("/", $_POST['Date']))) < strtotime("now")) {
      $this->form_validation->set_message("validationDate", "Tanggal reservasi harus di masa mendatang!");
      return false;
    } else {
      return true;
    };
  }

  // public function validationTime($post_array, $primary_key) {

  // }

  public function insert_facilityName($value, $primary_key) {
    return '<input type="text" maxlength="50" value="'.$this->manage->getFacilityName($_GET['FID']).'" name="ReqFacilityID" style="width:462px" disabled>
    <input type="text" maxlength="50" value="'.$_GET['FID'].'" name="ReqFacilityID" style="width:462px" hidden>';
  }

  public function insert_requester($value, $primary_key){
    return '<input type="text" maxlength="50" value="'.$_SESSION['account']['UserID'].'" name="RequesterID" style="width:462px" disabled>
    <input type="text" maxlength="50" value="'.$_SESSION['account']['UserID'].'" name="RequesterID" style="width:462px" hidden>';
  }

  public function insert_status($value, $primary_key){
    return '<input type="text" maxlength="50" value="Waiting For Approval" name="Status" style="width:462px" disabled>
    <input type="text" maxlength="50" value="Waiting For Approval" name="Status" style="width:462px" hidden>';
  }

  public function insert_StartTime(){
    return '<input type="time" name="StartTime" style="width:462px">';
  }

  public function insert_EndTime(){
    return '<input type="time" name="EndTime" style="width:462px">';
  }

  public function facilityDetail($id){
    $this->Auth->validateUserRole();
    $data['role'] = 'user';
    $this->load->library('grocery_CRUD');
    $crud = new grocery_CRUD();
    $crud->set_table('facility');
    $crud->set_theme('tablestrap');

    $output = $crud->render();
    $data['title'] = 'Booking Facility Website — Facility Datail';
    $data['crud'] = get_object_vars($output);
    $data['facility'] = $this->manage->printOneFacility($id);
    $this->template->load('template/template_navbar', 'pages/FacilityDetail', $data);
  }
}