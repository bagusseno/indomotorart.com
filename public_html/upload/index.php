<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
if(isset($_POST['title'])) {
  require_once('upload.php');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Upload - IndoMotorStyle</title>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

    if(!is_user_logged_in()) {
      header("Location: http://indomotorart.com");
      exit;
    }
    if(get_user_meta('token') != "activated") {
      header("Location: http://indomotorart.com/akun/verifikasi");
      exit;
    }

    // $nickname = get_user_meta('nickname');
    // $brand = get_user_meta('brand');
    // $tipe = get_user_meta('tipe');
    // $daerah = get_user_meta('daerah');
    //
    // if($nickname == null) {
    //   $nickname = "";
    // }
    // if($brand == null) {
    //   $brand = "";
    // }
    // if($tipe == null) {
    //   $tipe = "";
    // }
    // if($daerah == null) {
    //   $daerah = "";
    // }

    $msg_upload = "";
    $msg_title = "";
    $msg_motor = "";
    $msg_mod = "";

    ?>
  </head>
  <body>

    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php'); ?>
    <div class="page">
      <div class="container" style="max-width: 550px;">
        <h1>Upload</h1>
        <div class="q q_alert" id="q_upload">
            Perhatian! Dilarang mengupload foto yang mengandung unsur SARA dan PORNOGRAFI. Tidak juga diperkenankan mengupload foto motor dengan model yang mengumbar aurat.
        </div>
        <form id="upload-form" method="POST" action="" enctype="multipart/form-data">
          <div class="col-12" id="left">
              <dl class="upload-wrapper" style="max-width: 100%; border: 1px solid #ccc; min-height: 294px;display: flex; justify-content: center; align-items: center; flex-wrap: wrap">
                <dt id="img-field" class="u-side" style="display: none">
                  <img src="" id="img-file"/>
                </dt>
                <dt id="upload-file" class="u-side" style="position: absolute">
                  <label for="upload"><a id="upload-link" class="btn fa fa-plus" style="border:1px solid;cursor:pointer">Upload</a></label>
                  <input type="file" name="upload" id="upload" style="display: none" accept="image/*"></input>
                </dt>
              </dl>
            <dl class="errMsg center-text" id="msg-upload"><?php echo $msg_upload ?></dl>
              <label for="upload" id="ganti-link" style=" margin: 0 auto; display: none; margin-top: 11px; "><a class="btn fa fa-edit" style="border:1px solid;cursor:pointer">Ganti</a></label>
          </div>

          <div class="col-12" id="form">
            <dl class="form-group">
              <span class="errMsg right-text" id="msg-title"></span>
              <dt style="display:grid">
                  <div>
                <span class="form-label">
                  <label>Judul</label> 
                </span>
                <span class="form-input">
                  <input type="text" name="title" id="title" value=""/>
                </span>
                </div>
              </dt>
            </dl>
            <dl class="form-group">
                <span class="errMsg right-text" id="msg-motor"></span>
              <dt style="display:grid">
                <div>
                    <span class="form-label">
                        <label>Brand dan Tipe Motor</label> 
                    </span>
                    <span class="form-input">
                      <select name="brand" id="motor">
                        <option value="" disabled selected>Pilih Brand</option>
                        <option value="custom">Custom/Lain-lain</option>
                        <?php
                        $getBrands = $conn->query("SELECT * FROM brand");
                        $getBrands = $getBrands->fetch_all(MYSQLI_ASSOC);
                        foreach($getBrands as $br) {
                            $name = ucfirst($br[name]);
                            echo "<option value='$br[name]'>$name</option>";
                        }
                        
                        ?>
                      </select>
                        <?php 
                        foreach($getBrands as $br) {
                            $brName = $br[name];
                            $getTypes = $conn->query("SELECT * FROM tipe WHERE brandID='$brName'");
                            $getTypes = $getTypes->fetch_all(MYSQLI_ASSOC);
                            echo "<select id='$brName' name='$brName' class='tipe' style='display: none; margin-top: 13px'>
                            <option value='' disabled selected>Pilih tipe</option>
                            ";
                            foreach($getTypes as $ty) {
                                $name = $ty["name"];
                                echo "<option value='$name'>$name</option>";
                            }
                            echo "</select>";
                        }
                        ?>
                        <input style="margin-top: 13px" type="text" name="custom" id="custom" class='tipe' placeholder="Tipe/Aliran"/>
                        </span>
                </div>
              </dt>
            </dl>
            <dl class="form-group">
              <dt style="display:grid">
                <span class="form-label">
                  <label>Spesifikasi modifikasi</label> <span class="errMsg" id="msg-mod"/>
                </span>
                <span class="form-input">
                  <input type="text" name="mod" id="mod" value=""/>
                </span>
              </dt>
            </dl>
            <dl class="form-group">
              <dt style="display:grid">
                <span class="form-label">
                  <label>Caption/Keterangan</label> <span class="errMsg" id="msg-caption"/>
                </span>
                <span class="form-input">
                  <input type="text" name="caption" id="caption" value=""/>
                </span>
              </dt>
            </dl>
            <dl class="" id="submit">
              <dt class="form-input" style="/* special style */ width: 100%">
                <input type="hidden" id="input-tipe" name="tipe" value=""/>
                <input type="submit" class="btn" value="Upload" style="/* special style */ width: 100%!important; max-width: unset; height: 40px;"/>
              </dt>
            </dl>
          </div>
        </form>
      </div>
    </div>
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php'); ?>

  </body>
  <style>
  .col-12 {
    width:unset;
    box-sizing: border-box;
    margin-top: 20px;
  }
  .fa-home:before, .fa-image:before, .fa-star:before {
    padding-right: 3px;
  }
  #form {
    margin-bottom: 20px;
    display: grid;
    grid-gap: 10px;
  }
  @media all and (max-width: 700px) {
    #form {
      float: unset;
      width: 100%
    }
    .col-12 {
      width: 100%;
      float: unset!important;
    }
  }
  #left {
  }
  #img-field img {
    max-width: 100%;
  }
  .hallain {
    font-size: 30px;
    margin-top: 20px;
    text-decoration: none;
    max-width: 250px;
    display: table;
  }
  select {
    font-size: 20px;
  }
  #form input, select {
    float: right;
    box-sizing: border-box;
    width: 100%;
    height: 31px;
  }
  #submit {
      width: 100%;
  }
  .form-label {
    font-size: 20px;
  }
  .form-input {
    display: inline-block;
    float: right;
    width: 50%;
  }
  .errMsg {
    font-size: 12px;
    display:none;
    width: 100%;
  }
  @media screen and (max-width: 459px) {
      .form-input {
          width: 100%;
      }
      .form-input input, select {
          width: 100%;
      }
  }
  </style>
  <script type="text/javascript">

  $(document).ready(function() {
      var augusta = $("select[id='mv agusta']");
      augusta.attr("name", "mvagusta");
    // image preview
    $("#upload").unbind("change").bind("change", function() {

      if(this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $("#img-file").attr("src", e.target.result);
          $("#img-field").fadeIn("fast");
          if($("#img-file").attr("src") != ""){
            $("#upload-link").fadeOut("fast");
            $("#ganti-link").css("display","table");
          } else {
            $("#upload-link").fadeIn("fast");
            $("#ganti-link").fadeOut("fast");
          }
        }
        reader.readAsDataURL(this.files[0]);
      }
    })
    var merk = $("#motor").val();
    var tipe = $(merk).val();

    $("#motor").on("change", function() {
      console.log("Change");
      merk = $("#motor").val();
      tipe = $("#" + merk).val();
      $("#input-tipe").val(tipe);
      console.log(merk + tipe);
      if(merk == "honda") {
        tipe =
        $(".tipe").css("display", "none");
        $("#honda").css("display", "block");
      }
      if(merk == "yamaha") {
        $(".tipe").css("display", "none");
        $("#yamaha").css("display", "block");
      }
      if(merk == "kawasaki") {
        $(".tipe").css("display", "none");
        $("#kawasaki").css("display", "block");
      }
      if(merk == "suzuki") {
        $(".tipe").css("display", "none");
        $("#suzuki").css("display", "block");
      }
      if(merk == "mv\ agusta") {
        $(".tipe").css("display", "none");
        $("select[id='mv agusta']").css("display", "block");
      }
      if(merk == "piaggio\ vespa") {
        $(".tipe").css("display", "none");
        $("select[id='piaggio vespa']").css("display", "block");
      }
      if(merk == "aprilia") {
        $(".tipe").css("display", "none");
        $("#aprilia").css("display", "block");
      }
      if(merk == "bmw") {
        $(".tipe").css("display", "none");
        $("#bmw").css("display", "block");
      }
      if(merk == "ducati") {
        tipe =
        $(".tipe").css("display", "none");
        $("#ducati").css("display", "block");
      }
      if(merk == "ktm") {
        $(".tipe").css("display", "none");
        $("#ktm").css("display", "block");
      }
      if(merk == "benelli") {
        $(".tipe").css("display", "none");
        $("#benelli").css("display", "block");
      }
      if(merk == "harley-davidson") {
        $(".tipe").css("display", "none");
        $("#harley-davidson").css("display", "block");
      }
      if(merk == "bajaj") {
        tipe =
        $(".tipe").css("display", "none");
        $("#bajaj").css("display", "block");
      }
      if(merk == "kymco") {
        $(".tipe").css("display", "none");
        $("#kymco").css("display", "block");
      }
      if(merk == "custom") {
        $(".tipe").css("display", "none");
        $("#custom").css("display", "block");
      }
      if(merk == "") {
        $(".tipe").css("display", "none");
      }
    })

    $(".tipe").change(function() {
      merk = $("#motor").val();
      if(merk == "custom") {
        tipe = $("input[id='" + merk + "']").val();        
      } else {
        tipe = $("select[id='" + merk + "']").val();        
      }
      $("#input-tipe").val(tipe);
      console.log(tipe);
    })


    $("#upload-form").unbind("submit").bind("submit", function(e) {
      e.preventDefault();
      // variables
      var upload = $("#upload");
      var title = $("#title");
      var motor = $("#motor");
      var mod = $("#mod");
      var caption = $("#caption");
      // error messages
      var msg_upload = $("#msg-upload");
      var msg_title = $("#msg-title");
      var msg_motor = $("#msg-motor");
      var msg_mod  = $("#msg-mod");
      var msg_caption = $("#msg-caption");
      // error counter
      var error = 0;
      // validating
      if(upload.val() == "") {
        msg_upload.html("Anda belum memilih gambar");
        error++;
        msg_upload.fadeIn().css("display", "inline-block");
      } else {
        msg_upload.html("");
        msg_upload.fadeOut();
      }
      if(title.val() == "") {
        msg_title.html("Anda belum memasukkan judul");
        error++;
        msg_title.fadeIn().css("display", "inline-block");
      } else {
        msg_title.html("");
        msg_upload.fadeOut();
      }
      if(motor.val() == null) {
        msg_motor.html("Anda belum memilih motor");
        error++;
        msg_motor.fadeIn().css("display", "inline-block");
      } else {
        msg_motor.html("");
        msg_upload.fadeOut();
      }

      if(error == 0) {
        $(".btn").prop("disabled","true");
        this.submit();
      }
    })
    // $("#motor").change();

    // $("#complete-form").on("submit", function(e) {
    //   e.preventDefault();
    //   console.log("SAD");
    //   tipe = merk;
    //   if(merk == "honda") {
    //     tipe = $("#honda").val();
    //   }
    //   if(merk == "yamaha") {
    //     tipe = $("#yamaha").val();
    //   }
    //   if(merk == "kawasaki") {
    //     tipe = $("#kawasaki").val();
    //   }
    //   if(merk == "suzuki") {
    //     tipe = $("#suzuki").val();
    //   }
    //   var data = {
    //     nickname  : $("#nickname").val(),
    //     brand     : $("#motor").val(),
    //     tipe      : tipe,
    //     daerah    : $("#daerah").val()
    //   }
    //   $("#msg").html("Mengirim...");
    //   $.ajax({
    //     type: "POST",
    //     url: "http://indomotorart.com/function/setup.php",
    //     data: data,
    //     success: function(r) {
    //       console.log("KJSAKJSAD");
    //       if(r == "fail") {
    //         $("#msg").html("Terjadi kesalahan. Harap refresh dan ulangi.");
    //       }
    //       if(r == "success") {
    //         $("#msg").html("Berhasil!");
    //       }
    //     },
    //     error: function() {
    //       $("#msg").html("Terjadi kesalahan. Harap refresh dan ulangi.");
    //     }
    //   })
    // })
  })
  </script>
</html>
