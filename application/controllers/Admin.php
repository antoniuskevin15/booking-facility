<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('grocery_crud_model'); //Model untuk user
    $this->load->model('Auth');
    $this->Auth->validateAdminRole();
  }

  public function index(){
    redirect('admin/user');
  }

  public function user(){
      $data['role'] = "admin";
      $this->load->library('grocery_CRUD');
      $crud = new grocery_CRUD();
      $crud->set_theme('tablestrap');
      $crud->set_table('user');
      $crud->set_subject('User');
      $crud->columns('UserID','Username', 'Email', 'Role'); //Tampilkan semua kecuali password
      $crud->change_field_type('Password','password');
      $crud->edit_fields('Username', 'Email', 'Role');
      $crud->add_fields('Username', 'Email', 'Password', 'Role');
      $crud->set_relation('Role','role','RoleName');
      $crud->unset_print();
      $crud->unset_export();
      $crud->unset_read();
      $crud->unset_clone();

      //Untuk hash password
      $crud->callback_before_insert(array($this,'encrypt_password_callback'));
      $crud->callback_before_update(array($this,'encrypt_password_callback'));

      $output = $crud->render();
      $data['crud'] = get_object_vars($output);
      $data['title'] = 'Booking Facility Website — User Listing';
      $this->template->load('template/template_navbar', 'pages/UserListing', $data);
  }

  function encrypt_password_callback($post_array, $primary_key = null){
    $post_array['Password'] = password_hash($post_array['Password'], PASSWORD_DEFAULT);
    return $post_array;
  }

  public function facilities(){
      $data['role'] = "admin";
    $this->load->library('grocery_CRUD');
    $crud = new grocery_CRUD();
    $crud->set_theme('tablestrap');
    $crud->set_table('facility');
    $crud->set_subject('Facility');
    $crud->columns('FacilityID', 'FacilityName', 'Image');
    $crud->display_as('FacilityName','Facility Name');
    $crud->display_as('FacilityID','Facility ID');
    $crud->change_field_type('Password','password');
    $crud->edit_fields('FacilityName', 'Image');
    $crud->set_field_upload('Image','assets/images/facility');
    $crud->unset_print();
    $crud->unset_export();
    $crud->unset_read();
    $crud->unset_clone();

    $output = $crud->render();
    $data['title'] = 'Booking Facility Website — Facility Listing';
    $data['crud'] = get_object_vars($output);
    $this->template->load('template/template_navbar', 'pages/FacilityListing', $data);
  }

  public function requests(){  
      $data['role'] = "admin";
    $this->load->library('grocery_CRUD');
    $crud = new grocery_CRUD();
    $crud->set_theme('tablestrap');
    $crud->set_table('reserveduser');
    $crud->set_subject('Request');
    $crud->change_field_type('StartTime','time');
    $crud->change_field_type('EndTime','time');
    $crud->set_relation('RequesterID','user','Username');
    $crud->set_relation('ReqFacilityID','facility','FacilityName');
    $crud->unset_add();
    $crud->unset_edit();
    $crud->unset_print();
    $crud->unset_export();
    $crud->unset_read();

    $output = $crud->render();
    $data['crud'] = get_object_vars($output);
    $data['title'] = 'Booking Facility Website — Request Listing';
    $this->template->load('template/template_navbar', 'pages/RequestListing', $data);
}
}