<div class="intro" id="top-intro">

  <div class="container-1040" style="height:100%;display:flex;align-items: center">
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
    if(!is_user_logged_in()) { ?>
    <div class="col-13 top-intro-side left" id="log-col">
      <div class="form" id="top-form">
        <div class="tabs">
          <span class="tab tab-active" id="login-tab">
            Masuk
          </span>
          <span class="tab" id="register-tab">
            Daftar
          </span>
        </div>
        <form class="log-form" id="login-form">
          <dl class="form-group">
            <dt class="form-label">
              <label>Username</label> <span class="errMsg" id="msg-user"/>
            </dt>
            <dt class="form-input">
              <input type="text" id="username" name="username"/>
            </dt>
          </dl>
          <dl class="form-group">
            <dt class="form-label">
              <label>Password</label> <span class="errMsg" id="msg-pw"/>
            </dt>
            <dt class="form-input">
              <input type="password" id="password" name="password"/>
            </dt>
          </dl>
          <dl class="form-group errMsg" id="allError"></dl>
          <dl class="form-group" id="submit">
            <dt class="form-input">
              <input type="hidden" name="login"/>
              <input type="submit" class="btn" value="Masuk"/>
            </dt>
          </dl>
        </form>

        <form class="log-form" id="register-form" style="display:none">
          <dl class="form-group">
            <dt class="form-input">
              <input type="text" placeholder="Username" name="username" id="username-r"/>
            </dt>
          </dl>
          <dl class="form-group">
            <dt class="form-input">
              <input type="email" placeholder="E-mail" name="email" id="email-r"/>
            </dt>
          </dl>
          <dl class="form-group">
            <dt class="form-input">
              <input type="password" placeholder="Password" name="password" id="password-r"/>
            </dt>
          </dl>
          <dl class="form-group">
              <small><center>Dengan mendaftar saya menyatakan tunduk pada <a href="http://indomotorart.com/showpage.php?id=8">Kebijakan</a> yang berlaku.</center></small>
          </dl>
          <dl class="form-group errMsg" id="allError-r"></dl>
          <dl class="form-group" id="message-r"></dl>
          <dl class="form-group" id="submit">
            <dt class="form-input">
              <input type="hidden" name="register"/>
              <input type="submit" class="btn" value="Daftar"/>
            </dt>
          </dl>
        </form>
      </div>
    </div>

    <div class="col-23 top-intro-side" id="intro-text">
      <h1>DAFTAR DAN TUNJUKKAN KARYAMU!</h1>
      <p><?php echo $WEB_NAME ?> adalah website komunitas motor khususnya  motor custom Indonesia.</p>
    </div>
    <?php
      } else {
        echo '<style>.intro{height:233px!important}</style>';
    ?>
    <div class="col-11 top-intro-side intro-quote" id="intro-text">
      <h1>Upload Motormu dan Inspirasi Orang Lain</h1>
      <small>Nantikan kontes motor dari kami. Stay tune!</small>
    </div>
    <?php
      }
    ?>
  </div>

</div>

<style>
@media screen and (max-width: 929px) {

<?php
if(!is_user_logged_in()) {
?>
  #intro-text {
  display: none!important;
  }
<?php
} else {
  ?>
  #intro-text {
  display: inline-block!important;
  }
<?php
}
?>
</style>
