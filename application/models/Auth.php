<?php
class Auth extends CI_Model {
  public function __construct() {
    parent::__construct();
  }

  function register($username, $email, $password) {
    $data_user = array(
      'username' => $username,
      'email' => $email,
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'role' => 3
    );
    $this->db->insert('User', $data_user);
  }

  public function login($email, $password) {
    if ($query = $this->db->get_where('user', ['email' => $email])) {
      $query = $query->result_array();
      if (!$query) {
        $_SESSION['error'] = "Data yang anda masukkan salah!";
        return 0;
      }
      $query = $query[0];
    }


    if (password_verify($password, $query['Password']) && $query['Email'] == $email) {
      $_SESSION['account'] = $query;
      return 1;
    } else {
      $_SESSION['error'] = "Data yang anda masukkan salah!";
      return 0;
    }
  }

  public function validateAdminRole() {
    if(!isset($_SESSION['account']) || $_SESSION['account']['Role'] != '1') {
      redirect(base_url());
    }
  }
  
  public function validateManagementRole() {
    if(!isset($_SESSION['account']) || $_SESSION['account']['Role'] != '2') {
      redirect(base_url());
    }
  }

  public function validateUserRole() {
    if(!isset($_SESSION['account']) || $_SESSION['account']['Role'] != '3') {
      redirect(base_url());
    }
  }

  public function checkPermission($val = -1) {
    switch($val) {
      case 1:
        return "admin";
      case 2:
        return "management";
      case 3:
        return "user";
    }
  }
}
