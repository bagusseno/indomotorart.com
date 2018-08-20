<?php
// checking the referrer
if(isset($_GET['status'])) {
  if($_GET['status'] == "new") { // it means the register site refered to this page
    $h1 = "Lengkapi Akun Anda";
    $h2 = "Tambahkan informasi lain atau lewati.";
    $submit = "Lengkapi";
    $title = "Lengkapi Akun Anda - IndoMotorStyle";
  } else { // no refer code found?
    $h1 = "Pengaturan Akun";
    $h2 = "Ubah Pengaturan Akun Anda";
    $submit = "Ubah";
    $title = "Pengaturan Akun - IndoMotorStyle";
  }
} else { // no refer?
  $h1 = "Pengaturan Akun";
  $h2 = "Ubah Pengaturan Akun Anda";
  $submit = "Ubah";
  $title = "Pengaturan Akun - IndoMotorStyle";
}

// including important files
require_once($_SERVER['DOCUMENT_ROOT'] . '/asset.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/account.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/function/session.php');

// Unlogged in users are not allowed to access this page!
if(!is_user_logged_in()) {
  header("Location: http://indomotorart.com");
  exit;
}
// Unverified users are not allowed to access this page!
handle_token();
// getting existing data to be stored into variables
$existingAvatar = get_user_meta('avatar');
$nickname = get_user_meta('nickname');
$brand = get_user_meta('brand');
$tipe = get_user_meta('tipe');
$kota = get_user_meta('kota');
$daerah = get_user_meta('daerah');
// handling the $existingAvatar
if($existingAvatar == null) {
  $avatarDisplay = "none";
  $existingAvatar = "";
} else {
  $avatarDisplay = "block";
}
// if no existing data, or which means null, store a nothing. This to prevent echoing NULL word
if($nickname == null) {
  $nickname = "";
}
if($brand == null) {
  $brand = "";
}
if($tipe == null) {
  $tipe = "";
}
if($daerah == null) {
  $daerah = "";
}
if($kota == null) {
    $kota = "";
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
  </head>
  <body style="height:unset">

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/top/top-header-2.php');
    // settings up the message show, if it's user's first time or not
    
    ?>

    <div class="page">
      <div class="container-800 container-center">

        <h1><?php echo $h1 ?></h1>
        <h2 style="font-weight:100"><?php echo $h2 ?></h2>
        <div class="col-11"">
            
<form id="complete-form" enctype="multipart/form-data">
  <span class="col-13 form_pp">
    <label for="avatar">
      <div class="fa fa-cloud-upload" id="avatar-preview">
         <img src="<?php echo $existingAvatar ?>" style="display:<?php echo $avatarDisplay ?>" id="img-avatar"/>
      </div>
    </label>
    <input type="file" name="avatar" id="avatar" class="form_file_hidden" accept=".png .jpg .jpeg">
  </span>
  <span class="col-23 form_general">
    <ul>
      <li class="form-group">
        <dt class="form-input">
          <input type="text" name="nickname" id="nickname" class="form_input" placeholder="Nickname" value="<?php echo $nickname ?>"/>
        </dt>
      </li>
      <li class="form-group form_double_inline">
        <dt class="form-input">
          <select id="motor" class="form_select">
            <option value="" disabled selected>Pilih Brand</option>
            <option value="honda">Honda</option>
            <option value="yamaha">Yamaha</option>
            <option value="kawasaki">Kawasaki</option>
            <option value="suzuki">Suzuki</option>
          </select>
        </dt>
        <dt class="form-input">
          <select name="tipe" id="tipe" class="form_select" style="display:block!important" disabled>
            <option disabled selected>Pilih Tipe</option>
          </select>
          <select id="yamaha" class="tipe form_select" style="display:none">
            <option value='' disabled selected>Pilih Tipe</option>
            <option value='Aerox 125 LC'>Aerox 125 LC</option>
            <option value='All new Soul GT 125'>All new Soul GT 125</option>
            <option value='Grand Filano'>Grand Filano</option>
            <option value='GT 125'>GT 125</option>
            <option value='Jupiter MX'>Jupiter MX</option>
            <option value='Jupiter z1'>Jupiter z1</option>
            <option value='Mio M3 125'>Mio M3 125</option>
            <option value='Mio Z'>Mio Z</option>
            <option value='MT-09'>MT-09</option>
            <option value='MT-09 Tracer'>MT-09 Tracer</option>
            <option value='MT-25'>MT-25</option>
            <option value='MX king'>MX king</option>
            <option value='New Byson Fi'>New Byson Fi</option>
            <option value='New Fino 125'>New Fino 125</option>
            <option value='New Vixion'>New Vixion</option>
            <option value='Nmax'>Nmax</option>
            <option value='R1'>R1</option>
            <option value='R-15'>R-15</option>
            <option value='R-25'>R-25</option>
            <option value='R6'>R6</option>
            <option value='TMAX'>TMAX</option>
            <option value='Vega Force'>Vega Force</option>
            <option value='WR250R'>WR250R</option>
            <option value='Xabre'>Xabre</option>
            <option value='X-Ride'>X-Ride</option>
          </select>
          <select id="honda" class="tipe form_select" style="display:none">
            <option value='' disabled selected>Pilih Tipe</option>
            <option value='All new supra GTR'>All new supra GTR</option>
            <option value='Beat'>Beat</option>
            <option value='Beat pop'>Beat pop</option>
            <option value='Blade 125 FI'>Blade 125 FI</option>
            <option value='CB150R'>CB150R</option>
            <option value='CBR 150 R'>CBR 150 R</option>
            <option value='CBR 250'>CBR 250</option>
            <option value='MegaPro Fi'>MegaPro Fi</option>
            <option value='new Supra X '>new Supra X </option>
            <option value='Revo'>Revo</option>
            <option value='Scoopy'>Scoopy</option>
            <option value='Sonic 150R'>Sonic 150R</option>
            <option value='Spacy'>Spacy</option>
            <option value='Vario 110 Fi'>Vario 110 Fi</option>
            <option value='Vario 125 '>Vario 125 </option>
            <option value='Vario 150'>Vario 150</option>
            <option value='Verza'>Verza</option>
          </select>
          <select id="kawasaki" class="tipe form_select" style="display:none">
            <option value='' disabled selected>Pilih Tipe</option>
            <option value='Athlete PRO'>Athlete PRO</option>
            <option value='D-Tracker'>D-Tracker</option>
            <option value='D-tracker SE'>D-tracker SE</option>
            <option value='D-TRACKER X'>D-TRACKER X</option>
            <option value='ER-6n'>ER-6n</option>
            <option value='Estrella'>Estrella</option>
            <option value='KSR 110'>KSR 110</option>
            <option value='KSR PRO'>KSR PRO</option>
            <option value='Ninja 1000'>Ninja 1000</option>
            <option value='Ninja 250'>Ninja 250</option>
            <option value='Ninja 650'>Ninja 650</option>
            <option value='Ninja H2'>Ninja H2</option>
            <option value='Ninja R'>Ninja R</option>
            <option value='Ninja RR'>Ninja RR</option>
            <option value='Ninja SS'>Ninja SS</option>
            <option value='Ninja ZX-10R'>Ninja ZX-10R</option>
            <option value='Ninja ZX-14R'>Ninja ZX-14R</option>
            <option value='Ninja ZX-6R'>Ninja ZX-6R</option>
            <option value='Pulsar 200NS'>Pulsar 200NS</option>
            <option value='Versys 1000'>Versys 1000</option>
            <option value='Versys 650'>Versys 650</option>
            <option value='Vulcan S'>Vulcan S</option>
            <option value='Vulcan S SE'>Vulcan S SE</option>
            <option value='W800'>W800</option>
            <option value='Z1000'>Z1000</option>
            <option value='Z125 PRO'>Z125 PRO</option>
            <option value='Z250'>Z250</option>
            <option value='Z250SL'>Z250SL</option>
            <option value='Z800'>Z800</option>
          </select>
          <select id="suzuki" class="tipe form_select" style="display:none">
            <option value='value=' disabled selected>Pilih Tipe</option>
            <option value='Address'>Address</option>
            <option value='BURGMAN 200'>BURGMAN 200</option>
            <option value='Hayabusa'>Hayabusa</option>
            <option value='Satria'>Satria</option>
            <option value='Suzuki Inazuma R'>Suzuki Inazuma R</option>
            <option value='V-Strom 650'>V-Strom 650</option>
          </select>
        </dt>
      </li>
      <li class="form-group form_double_inline">
        <dt class="form-input ">
          <select name="kota" id="kota" class="form_select">
            <option value="" disabled selected>Pilih Kota</option>
            <option value='Banda Aceh'>Banda Aceh</option>
            <option value='Langsa'>Langsa</option>
            <option value='Lhokseumawe'>Lhokseumawe</option>
            <option value='Meulaboh'>Meulaboh</option>
            <option value='Sabang'>Sabang</option>
            <option value='Subulussalam'>Subulussalam</option>
            <option value='Denpasar'>Denpasar</option>
            <option value='Pangkalpinang'>Pangkalpinang</option>
            <option value='Cilegon'>Cilegon</option>
            <option value='Serang'>Serang</option>
            <option value='Tangerang'>Tangerang</option>
            <option value='Tangerang Selatan'>Tangerang Selatan</option>
            <option value='Jakarta Barat'>Jakarta Barat</option>
            <option value='Jakarta Timur'>Jakarta Timur</option>
            <option value='Jakarta Selatan'>Jakarta Selatan</option>
            <option value='Jakarta Utara'>Jakarta Utara</option>
            <option value='Jakarta Pusat'>Jakarta Pusat</option>
            <option value='Sungai Penuh'>Sungai Penuh</option>
            <option value='Jambi'>Jambi</option>
            <option value='Bandung'>Bandung</option>
            <option value='Bekasi'>Bekasi</option>
            <option value='Bogor'>Bogor</option>
            <option value='Cimahi'>Cimahi</option>
            <option value='Cirebon'>Cirebon</option>
            <option value='Depok'>Depok</option>
            <option value='Sukabumi'>Sukabumi</option>
            <option value='Tasikmalaya'>Tasikmalaya</option>
            <option value='Banjar'>Banjar</option>
            <option value='Magelang'>Magelang</option>
            <option value='Pekalongan'>Pekalongan</option>
            <option value='Purwokerto'>Purwokerto</option>
            <option value='Salatiga'>Salatiga</option>
            <option value='Semarang'>Semarang</option>
            <option value='Surakarta'>Surakarta</option>
            <option value='Tegal'>Tegal</option>
            <option value='Batu'>Batu</option>
            <option value='Blitar'>Blitar</option>
            <option value='Kediri'>Kediri</option>
            <option value='Madiun'>Madiun</option>
            <option value='Malang'>Malang</option>
            <option value='Mojokerto'>Mojokerto</option>
            <option value='Pasuruan'>Pasuruan</option>
            <option value='Probolinggo'>Probolinggo</option>
            <option value='Surabaya'>Surabaya</option>
            <option value='Pontianak'>Pontianak</option>
            <option value='Singkawang'>Singkawang</option>
            <option value='Banjarbaru'>Banjarbaru</option>
            <option value='Banjarmasin'>Banjarmasin</option>
            <option value='Palangkaraya'>Palangkaraya</option>
            <option value='Balikpapan'>Balikpapan</option>
            <option value='Bontang'>Bontang</option>
            <option value='Samarinda'>Samarinda</option>
            <option value='Tarakan'>Tarakan</option>
            <option value='Batam'>Batam</option>
            <option value='Tanjungpinang'>Tanjungpinang</option>
            <option value='Bandar '>Bandar </option>
            <option value='Metro'>Metro</option>
            <option value='Ternate'>Ternate</option>
            <option value='Tidore Kepulauan'>Tidore Kepulauan</option>
            <option value='Ambon'>Ambon</option>
            <option value='Tual'>Tual</option>
            <option value='Bima'>Bima</option>
            <option value='Mataram'>Mataram</option>
            <option value='Kupang'>Kupang</option>
            <option value='Sorong'>Sorong</option>
            <option value='Jayapura'>Jayapura</option>
            <option value='Dumai'>Dumai</option>
            <option value='Pekanbaru'>Pekanbaru</option>
            <option value='Makassar'>Makassar</option>
            <option value='Palopo'>Palopo</option>
            <option value='Parepare'>Parepare</option>
            <option value='Palu'>Palu</option>
            <option value='Bau-Bau'>Bau-Bau</option>
            <option value='Kendari'>Kendari</option>
            <option value='Bitung'>Bitung</option>
            <option value='Kotamobagu'>Kotamobagu</option>
            <option value='Manado'>Manado</option>
            <option value='Tomohon'>Tomohon</option>
            <option value='Bukittinggi'>Bukittinggi</option>
            <option value='Padang'>Padang</option>
            <option value='Padangpanjang'>Padangpanjang</option>
            <option value='Pariaman'>Pariaman</option>
            <option value='Payakumbuh'>Payakumbuh</option>
            <option value='Sawahlunto'>Sawahlunto</option>
            <option value='Solok'>Solok</option>
            <option value='Lubuklinggau'>Lubuklinggau</option>
            <option value='Pagaralam'>Pagaralam</option>
            <option value='Palembang'>Palembang</option>
            <option value='Prabumulih'>Prabumulih</option>
            <option value='Binjai'>Binjai</option>
            <option value='Medan'>Medan</option>
            <option value='Padang Sidempuan'>Padang Sidempuan</option>
            <option value='Pematangsiantar'>Pematangsiantar</option>
            <option value='Sibolga'>Sibolga</option>
            <option value='Tanjungbalai'>Tanjungbalai</option>
            <option value='Tebingtinggi'>Tebingtinggi</option>
            <option value='Yogyakarta'>Yogyakarta</option>
          </select>
        </dt>
        <dt class="form-input ">
          <input type="text" name="daerah" id="daerah" class="form_input" placeholder="Daerah" value="<?php echo $daerah ?>">
        </dt>
      </li>
      <li class="form-submit">
        <?php if($_GET['status'] == "new") { ?><span class="btn_transparent"><a href="http://indomotorart.com/index.php?status=new">Lewati</a></span><?php } ?>
        <span>
          <input type="submit" class="btn form_submit" value="Simpan">
        </span>
      </li>
    </ul>
  </span>
</form>

<style>
.form_pp {
  width: 138px!important;
    margin: 0 auto;
    display: block;
}
.form_pp img {
  width: 100%;
}
.form_general {
  float: right;
    width: 100%;
    margin-top: 25px;
    padding-left: 10px;
    box-sizing: border-box;
}
.form_general ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  grid-gap: 10px;
}
.form_file_hidden {
  display: none;
}
#complete-form {
  width: 100%;
  margin-top: 25px;
}
.form_input {
  width: 100%;
}
.form_select {
  width: 100%;
}
.form_input, .form_select {
  padding: 3px;
  border: 1px solid;
  min-height: 40px;
  font-family: Roboto Slab;
  font-size: 20px;
    padding: 5px 8px!important; 
}
.form_double_inline {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 10px;
}
.form-submit {
  text-align: right;
}
</style>
      </div>
    </div>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/parts/bottom.php');

    ?>
  </body>
  <style>
  .col-12 {
    float: left;
    box-sizing: border-box;
  }
  .fa-home:before, .fa-image:before, .fa-star:before {
    padding-right: 3px;
  }
  #avatar-preview {
    max-width: 138px;
    width: 100%;
    height:138px;
    border: 1px solid;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    cursor: pointer;
    box-sizing: border-box;
  }
  #avatar-preview:before {
    position: absolute;
    color: black;
  }
  #img-avatar {
    width: 100%;
    height: inherit;
  }
  select {
    width: 251px;
    border: 1px solid;
  }
  </style>
  <script type="text/javascript">

  $(document).ready(function() {

    $("#avatar").unbind("change").bind("change", function() {

      if(this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $("#img-avatar").attr("src", e.target.result);
          $("#img-avatar").fadeIn("fast");
          $("#avatar-preview:before").css("color", "white");
        }
        reader.readAsDataURL(this.files[0]);
      }
    })

    $("#nickname").focus();
    $("#motor").val("<?php echo $brand ?>");
    $("#kota").val("<?php echo $kota ?>");
    var merk = $("#motor").val();
    $("#" + merk).val("<?php echo $tipe ?>");
    var tipe = "<?php echo $tipe ?>";
    var changeCounter = 0; // this is to prevent the tipe select box to not hiding when the brand is changed if the tipe has not been selected before
    $("#motor").on("change", function() {
      console.log("Change");
      if(tipe != "" || changeCounter != 0) {
        $("#tipe").css("display", "none");
      }
      changeCounter = 1;
      merk = $("#motor").val();
      if(merk == "honda") {
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
      if(merk == "") {
        $(".tipe").css("display", "none");
      }
    })
    $("#motor").change();

    $("#complete-form").on("submit", function(e) {
      e.preventDefault();
      console.log("SAD");
      tipe = merk;
      if(merk == "honda") {
        tipe = $("#honda").val();
      }
      if(merk == "yamaha") {
        tipe = $("#yamaha").val();
      }
      if(merk == "kawasaki") {
        tipe = $("#kawasaki").val();
      }
      if(merk == "suzuki") {
        tipe = $("#suzuki").val();
      }
      var avatar = $("#avatar").get(0).files[0];
      var $form = new FormData();
      $form.append('avatar', avatar);
      $form.append('nickname', $("#nickname").val());
      $form.append("brand", $("#motor").val());
      $form.append("tipe", tipe);
      $form.append("kota", $("#kota").val());
      $form.append("daerah", $("#daerah").val());
    
    if($("#nickname").val() == "") {
        alert("Nickname tidak boleh kosong :). Silakan klik lewati jika tidak berkenan memasukkan nickname.");
        } else {
          if($("#avatar").length != 0) {
            $(".form_submit").html("Mengupload...");
            $(".form_submit").attr("disabled", "disabled");
          }
          
          $.ajax({
            type: "POST",
            url: "http://indomotorart.com/function/setup.php",
            data: $form,
            processData: false,
            contentType: false,
            success: function(r) {
              console.log("KJSAKJSAD" + r);
              if(r == "fail") {
                $("#msg").html("Terjadi kesalahan. Harap refresh dan ulangi." + r);
              } else if(r == "success") {
                window.location.replace("http://indomotorart.com/index.php?status=new");
              } else if(r == "erroravatar") {
                $("#msg").html("Error mengupload foto profil. Harap refresh dan ulangi."  + r);
              } else {
                $("#msg").html("Terjadi kesalahan. Harap refresh dan ulangi."  + r);
              }
            },
            error: function() {
              $("#msg").html("Terjadi kesalahan. Harap refresh dan ulangi. " + r);
            }
          })
      }
    })
  })
  </script>
</html>
