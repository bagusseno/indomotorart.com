// put cursor at end function
$.fn.putCursorAtEnd = function() {
  $initialVal = this.val();
  this.val($initialVal + ' ');
  this.val($initialVal);
}

$(document).ready(function(){

  // creating menu bar float system
  $(window).scroll(function() {
    if($(window).scrollTop() > $("#top-intro").height() + $("#mid-header").height()) {
      $("#float-header").slideDown("fast");
      $("#float-header").css("margin-top", 0);
    } else {
      $("#float-header").slideUp("down");
      $(".mid-nav").css("margin-top", "15px");
    }

  })

  // creating
  $(".tab").click(function() {
    $(".tab").removeClass('tab-active');
    if($(this).attr('ID') == "login-tab") {
      $(".log-form").css('display', 'none');
      $("#login-form").fadeIn('fast');
      $("#top-intro").css('height', '360px');
    } else {
      $(".log-form").css('display', 'none');
      $("#register-form").fadeIn('fast');
      $("#top-intro").css('height', '415px');
    }
    $(this).addClass('tab-active');
  })

  $("#login-form").unbind("submit").bind("submit", function(e) {
    e.preventDefault();
    console.log("Try to submit");
    var username = $("#username");
    var password = $("#password");
    var error = 0;

    if(username.val() == "") {
      username.css('border', '1px solid red');
      $("#msg-user").html("<font color=red> <small>Username tidak boleh kosong</small></font>");
      error++;
    } else {
      username.css('border', '1px solid');
      $("#msg-user").html("");
    }

    if(password.val() == "") {
      password.css('border', '1px solid red');
      $("#msg-pw").html("<font color=red> <small>Password tidak boleh kosong</small>  </font>");
      error++;
    } else {
      password.css('border', '1px solid');
      $("#msg-pw").html("");
    }
    console.log(error);

    if(error == 0) {
      $.ajax({
        type: "POST",
        url: "http://indomotorart.com/function/login.php",
        data: {
          login : "login",
          username : username.val(),
          password : password.val()
        },
        success: function(r) {
          console.log("Logged in" + r + username.val() + password.val());
          if(r == "notfound") {
            $("#allError").html("<center><p>Username atau password salah</p></center>");
          }
          if(r == "already") {
            console.log("LogEGEd");
          }
          if(r == "loggedin") {
            location.replace("http://indomotorart.com/myfeed");
          }
        }
      });
    }

  });

  $("#register-form").unbind("submit").bind("submit", function(e) {
    e.preventDefault();
    console.log("Trying to submit");
    var username = $("#username-r");
    var email = $("#email-r");
    var password = $("#password-r");
    var error = 0;

    if(username.val() == "") {
      username.css('border', '1px solid red');
      $("#msg-user-r").html("<small>Username tidak boleh kosong</small>");
      error++;
    } else if(username.val().indexOf(" ") != -1) {
      $("#msg-user-r").html("<small>Username tidak boleh ada spasi</small>");
      error++;
    } else {
      username.css('border', '1px solid black');
      $("#msg-user-r").html("");
    }

    if(email.val() == "") {
      email.css('border', '1px solid red');
      $("#msg-email-r").html("<small>E-mail tidak boleh kosong");
      error++;
    } else {
      email.css('border', '1px solid black');
      $("#msg-email-r").html("");
    }
    if(password.val() == "") {
      password.css('border', '1px solid red');
      $("#msg-pw-r").html("<small>Password tidak boleh kosong");
      error++;
    } else {
      password.css('border', '1px solid black');
      $("#msg-pw-r").html("");
    }

    if(error == 0) {
        
        $("#message-r").fadeIn();
      $("#message-r").html("Silakan tunggu...");

      var data = {
        register  : "register",
        username  : username.val(),
        password  : password.val(),
        email     : email.val()
      };
      $.ajax({
        type: "POST",
        url: "http://indomotorart.com/function/register.php",
        data: data,
        success: function(r) {
          console.log("registered");
          if(r == "alreadyUser") {
            $("#msg-user-r").html("Username ini telah digunakan");
            $("#message-r").fadeOut().html("");
          } else {
            $("#msg-user-r").html("");
          }
          if(r == "alreadyEmail") {
            $("#msg-email-r").html("E-mail ini telah digunakan");
            $("#message-r").fadeOut().html("");
          } else {
            $("#msg-email-r").html("");
          }

          if(r == "alreadyUserEmail") {
            $("#msg-user-r").html("Username ini telah digunakan");
            $("#msg-email-r").html("E-mail ini telah digunakan");
            $("#message-r").html("");
          }
          if(r == "error") {
            $("#allError-r").html("Terjadi kesalahan. Harap ulangi");
          }
          if(r == "success") {
            window.location.replace("http://indomotorart.com/akun/pengaturan?status=new");
          }
          console.log(r);
        }
      });
    }
  });

  var dropped = 0;
  var dropped_2 = 0;

  $("#account-icon").unbind("click").bind("click", function() {
    $(".dropdown").css('display', 'none');
    dropped_2 = 0;
    if(dropped == 0){
      $("#dropdown-user").css('display', 'block');
      dropped = 1;
      return;
    }
    if(dropped == 1) {
      $("#dropdown-user").css('display', 'none');
      dropped = 0;
      return;
    }
    
  });

  $("#account-icon-2").unbind("click").bind("click", function() {
    $(".dropdown").css('display', 'none');
    dropped = 0;
    if(dropped_2 == 0){
      $("#dropdown-user-2").css('display', 'block');
      dropped_2 = 1;
      return;
    }
    if(dropped_2 == 1) {
      $("#dropdown-user-2").css('display', 'none');
      dropped_2 = 0;
      return;
    }
  });

  $(window).click(function(e) {
    if(!e.target.matches('#account-icon')) {
      if(dropped == 1) {
        $("#dropdown-user").css('display', 'none');
        dropped = 0;
        return;
      }
    }
    if(!e.target.matches('#account-icon-2')) {
      if(dropped_2 == 1) {
        $("#dropdown-user-2").css('display', 'none');
        dropped_2 = 0;
        return;
      }
    }
  });

  var open = 0;
  $(".burger img").unbind("click").bind("click", function() {
    console.log("asdas");

    if(open == 0) {
      $(".burger-header").slideDown("slow");
      open++;
    } else {
      $(".burger-header").slideUp("slow");
      open = 0;
    }
  })
})
