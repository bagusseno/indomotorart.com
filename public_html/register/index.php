<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Daftar - IndoMotorART</title>
  </head>
  <body>
    <?php
    require_once('../asset.php');
    require_once('../parts/top/top-header.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');
    if(is_user_logged_in()) {
      header("Location: http://indomotorart.com");
      exit;
    }
    ?>
    <div class="fullwrap">
      <div class="container-1040 container-flex-center">
        <?php
        require_once($_SERVER['DOCUMENT_ROOT'] . "/function/account.php");
        if(!is_user_logged_in()) { ?>
        <div class="col-13 top-intro-side left" id="log-col">
          <div class="form" id="top-form">
            <div class="tabs">
              <span class="tab tab-active" id="register-tab">
                Daftar
              </span>
            </div>
            <form class="log-form" id="register-form" style="">
              <dl class="form-group">
                <dt class="form-label">
                  <label>Username</label> <span class="errMsg" id="msg-user-r"/>
                </dt>
                <dt class="form-input">
                  <input type="text" name="username" id="username-r"/>
                </dt>
              </dl>
              <dl class="form-group">
                <dt class="form-label">
                  <label>E-mail</label> <span class="errMsg" id="msg-email-r"/>
                </dt>
                <dt class="form-input">
                  <input type="email" name="email" id="email-r"/>
                </dt>
              </dl>
              <dl class="form-group">
                <dt class="form-label">
                  <label>Password</label> <span class="errMsg" id="msg-pw-r"/>
                </dt>
                <dt class="form-input">
                  <input type="password" name="password" id="password-r"/>
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
    padding-top: 50px;
  }
  .top-intro-side {
    margin-top: 0!important;
  }
  </style>
<script type="text/javascript">
    $(document).ready(function() {
        while($("iframe").length) {
            $("iframe").remove();  
            console.log("HAAA")
        }
    });
  </script>
</html>
