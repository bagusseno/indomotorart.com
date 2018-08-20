<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Masuk - IndoMotorART</title>
  </head>
  <body>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
    if(is_user_logged_in()) {
      header("Location: http://indomotorart.com");
      exit;
    }
    require_once('../asset.php');
    require_once('../parts/top/top-header.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
    ?>
    <div class="fullwrap">
      <div class="container-1040 container-flex-center">
        <?php
        if(!is_user_logged_in()) { ?>
        <div class="col-13 top-intro-side left" id="log-col">
          <div class="form" id="top-form">
            <div class="tabs">
              <span class="tab tab-active" id="login-tab">
                Masuk
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
          </div>
        </div>

        <div class="col-23 top-intro-side" id="intro-text">
          <h1>DAFTAR DAN TUNJUKKAN KARYAMU!</h1>
          <p><?php echo $WEB_NAME ?> adalah website komunitas motor khususnya motor custom Indonesia.</p>
        </div>
        <?php
          } else {
        ?>
        <div class="col-11 top-intro-side intro-quote" id="intro-text">
          <h1>Tidak Pernah Menyerah Dalam Hobi</h1>
          <small>Oleh Bagus Seno "Jupiter MX Twin Turbo"</small>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
  </body>
  <style type="text/css">
  html {
    height: 100%;
    width: 100%;
  }
  body {
    height: 100%;
    width: 100%;
    background-image: url('https://images.pexels.com/photos/533540/pexels-photo-533540.jpeg?w=940&h=650&auto=compress&cs=tinysrgb');
    background-repeat: no-repeat;
    background-position-x: center;
    background-size: cover;
    position: relative;
  }
  body:before {
    position: absolute;
    width: 100%;
    height: 100%;
    background: black;
    top: 0;
    opacity: 0.8;
    content: " ";
  }
  .fullwrap {
    height: calc(100% - 50px);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .top-intro-side {
    margin-top: 0!important;
  }
  </style>
</html>
