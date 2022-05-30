<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container login__wrapper" data-aos="zoom-in">
  <h1 class="text-center mb-5 login__main-title">Login Here</h1>
  <?php 
			if($this->session->flashdata('errorCaptchaL') !='')
			{
				echo '<div class="alert alert-danger" role="alert">';
				echo $this->session->flashdata('errorCaptchaL');
				echo '</div>';
			}
		?>
  <form action="<?= site_url("/home/login") ?>" method="POST">
    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input class="form-control" id='email' type="email" name="email" placeholder="Input Email" required>
    </div>
    <?= form_error('email', "<p class='alert alert-danger'>", "</p>") ?>

    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input class="form-control" id='password' type="password" name="password" placeholder="Input Password" required>
    </div>
    <?= form_error('password', "<p class='alert alert-danger'>", "</p>") ?>

    <?php
      if (isset($_SESSION['error'])) {
        echo "<p style='color: red'>" . $_SESSION['error'] . "<p>";
        unset($_SESSION['error']);
      }
    ?>
    <div class="mb-3">
      <!-- <div class="g-recaptcha" data-sitekey="6LdnJXwdAAAAALqGq9_yDkY5iAeuPPq6MvfZvsGi"></div> -->
      <!-- untuk localhost -->
      <div class="g-recaptcha" data-sitekey="6Lf6Cm8dAAAAAGflr6iNKHEh6x27kZ2OScKS7anq"></div> 
    </div>
    <button class="btn login__button" type="submit">Submit</button>
  </form>

  <div>
    <p class="text-center mt-5 register__footer">Does not have an account?
      <a href="<?= base_url('index.php/home/register'); ?>" class="register__redirect">Register</a> now!
    </p>
  </div>
</div>