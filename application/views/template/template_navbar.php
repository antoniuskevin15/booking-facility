<?php
  $navbar = [
    "admin" => [
      "user" => site_url("admin/user"), 
      "facilities" => site_url("admin/facilities"),
      "requests" => site_url("admin/requests")
    ],
    "management" => [
      "facilities" => site_url("management/facilities"),
      "requests" => site_url("management/requests")
    ],
    "user" => [
      "facilities" => site_url("user/facilities"),
      "requests" => site_url("user/requests")
    ]
  ];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="<?= base_url('assets/images/icon.png'); ?>" type="image/png">
  <title><?= $title; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <?php foreach ($crud['css_files'] as $file): ?>
  <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
  <?php endforeach; ?>
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/main.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/main-responsive.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid navbar__responsive">
      <a class="navbar-brand" href="<?= base_url() ?>">Facility Boooking</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <?php
            foreach($navbar[$role] as $key => $value) {
            echo "
              <li class='nav-item navbar__menu-item ";
              if ($this->uri->segment(2, NULL) == $key) echo 'currentPage';
              echo "'>
                <a class='nav-link' href='$value'>$key</a>
              </li>
            ";
            }
          ?>
      </div>
      <div class="d-flex align-items-center profile-wrapper">
        <a class="profile-name" href="#">
          Hi, <?php echo (isset($_SESSION["account"])) ? $_SESSION["account"]['Username'] : "Please Login!"; ?></a>
        <!-- controller method tidak ada && status belum login ? -->
        <a class="btn navbar__button-logout"
          onclick="logout()"><?php echo (isset($_SESSION["account"])) ? "Logout" : "Login"; ?>
        </a>
        <!-- controller method tidak ada && status belum login ? -->
      </div>
    </div>
  </nav>
  <?= $contents; ?>

  <script src=' https://code.jquery.com/jquery-3.5.1.js'>
  </script>
  <script src='https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js'></script>
  <script src='https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
  </script>
  <?php foreach ($crud['js_files'] as $file): ?>
  <script src="<?php echo $file; ?>"> </script>
  <?php endforeach; ?>
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
  AOS.init();

  const isUser = location.href.split('/').findIndex(val => val === 'user');
  const isAdmin = location.href.split('/').findIndex(val => val === 'admin')

  if (isUser !== -1 && isAdmin === -1) {
    $('.add_button').hide()
    $('.add_button').removeClass('hidden-xs')
  }

  function logout() {
    document.location.href = "<?= site_url("home/logout") ?>";
  }

  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
  </script>
</body>

</html>