<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('auth');
    $this->load->library('form_validation');
    $this->load->library('session');
  }

  public function index() {
    $data['title'] = 'Booking Facility Website — Kelompok 2';

    $this->template->load('template/template_home', 'home', $data);
  }

  public function logout() {
    session_unset();
    session_destroy();
    redirect("Home");
  }

  //Buka view register
  public function register() {
    $data['title'] = 'Booking Facility Website — Register';
    $this->template->load('template/template_home', 'pages/register', $data);
  }

  //Cek Rules Register
  public function registCheck() {
    $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[1]|max_length[255]|is_unique[user.username]');
    $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[1]|max_length[255]|valid_email');
    $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[1]|max_length[255]');
    if ($this->form_validation->run() == true) //Kalau sesuai rules insert ke DB
    {
      $username = $this->input->post('username');
      $email = $this->input->post('email');
      $password = $this->input->post('password');
      $captcha_response = trim($this->input->post('g-recaptcha-response'));

      if($captcha_response != '')
      {
        // $keySecret = '6LdnJXwdAAAAADJSD9sHpyWzJzY_8Bnf1HITyfal';
        // key untuk localhost
        $keySecret = '6Lf6Cm8dAAAAAM3xM1v2kY9ichIo9tsyTMW9tsuw'; 
        $check = array(
          'secret'		=>	$keySecret,
          'response'		=>	$this->input->post('g-recaptcha-response')
        );

        $startProcess = curl_init();

        curl_setopt($startProcess, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");

        curl_setopt($startProcess, CURLOPT_POST, true);

        curl_setopt($startProcess, CURLOPT_POSTFIELDS, http_build_query($check));

        curl_setopt($startProcess, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($startProcess, CURLOPT_RETURNTRANSFER, true);

        $receiveData = curl_exec($startProcess);

        $finalResponse = json_decode($receiveData, true);

        if($finalResponse['success'])
        {
          $this->auth->register($username, $email, $password);
          $this->session->set_flashdata('success_register', 'Proses Pendaftaran User Berhasil');
          redirect('home/login'); //Terus masuk ke login;
        } else
        {
          $this->session->set_flashdata('errorCaptchaR', 'Validation Fail Try Again');
          redirect('home/register');
        }
      } else
      {
        $this->session->set_flashdata('errorCaptchaR', 'Validation Fail Try Again');
        redirect('home/register');
      }
    } else //Kalau ga sesuai balik ke register + bawa validation errornya
    {
      $this->session->set_flashdata('error', validation_errors());
      redirect('home/register');
    }
  }

  //Menampilkan view login
  public function login() {
    if(isset($_SESSION['account'])) {
      redirect("{$this->auth->checkPermission($_SESSION['account']['Role'])}");
    }
    $data['title'] = 'Booking Facility Website — Login';

    $loginStatus = 0;
    $this->form_validation->set_rules('email', 'Email', "valid_email");
        if ($this->input->post('email') != null && $this->input->post('password') != NULL) {
          $captcha_response = trim($this->input->post('g-recaptcha-response'));

          if($captcha_response != '')
          {
            // $keySecret = '6LdnJXwdAAAAADJSD9sHpyWzJzY_8Bnf1HITyfal';
            // key untuk localhost
            $keySecret = '6Lf6Cm8dAAAAAM3xM1v2kY9ichIo9tsyTMW9tsuw'; 

            $check = array(
              'secret'		=>	$keySecret,
              'response'		=>	$this->input->post('g-recaptcha-response')
            );

            $startProcess = curl_init();

            curl_setopt($startProcess, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");

            curl_setopt($startProcess, CURLOPT_POST, true);

            curl_setopt($startProcess, CURLOPT_POSTFIELDS, http_build_query($check));

            curl_setopt($startProcess, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($startProcess, CURLOPT_RETURNTRANSFER, true);

            $receiveData = curl_exec($startProcess);

            $finalResponse = json_decode($receiveData, true);

            if($finalResponse['success'])
            {
            $loginStatus = $this->auth->login($this->input->post('email'), $this->input->post('password'));
            } else
            {
              $this->session->set_flashdata('errorCaptchaL', 'Validation Fail Try Again');
              redirect('home/login');
            }
          } else
          {
            $this->session->set_flashdata('errorCaptchaL', 'Validation Fail Try Again');
            redirect('home/login');
          }
        }

        if (!$this->form_validation->run() || !$loginStatus) {
          $this->template->load('template/template_home', 'pages/login', $data); //login gagal
        } else if($_SESSION['account']['Role'] == 1) {
          redirect("admin");
        } else if($_SESSION['account']['Role'] == 2) {
          redirect("management");
        } else if($_SESSION['account']['Role'] == 3) {
          redirect("user");
        }
  }
}