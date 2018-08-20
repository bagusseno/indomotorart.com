<?php
?>

<div class="overlay">
    <div class="popup" id="welcome_popup">
        <div class="p_title">
            Selamat datang di IndoMotorART
        </div>
        <div class="p_content">
            Ayo bergabung dengan komunitas IndoMotorART! <a href="http://indomotorart.com/login">Login</a> atau <a href="http://indomotorart.com/daftar">Daftar</a>.
        </div>
        <div class="p_action">
            <a href="javascript:void(0)" onclick="closePopup()" class="btn_transparent" style="color:grey">Tutup</a>
            <a href="http://indomotorart.com/daftar" class="btn">Daftar</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    function closePopup() {
        $(".overlay").remove();
    }
</script>
<style>
.overlay {
    z-index: 99999999999999;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    /* opacity: 0.5; */
    position: fixed;
    display: flex;
    align-items: center;
    font-family: Roboto Slab;
    padding: 0 15px;
    justify-content: center;
    box-sizing: border-box;
    top: 0;
}
.popup {
    max-width: 545px;
    max-height: 500px;
    /* width: 100%; */
    /* height: 100%; */
    background: white;
    /* position: absolute; */
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    /* font-family: Roboto Slab; */
}
.p_title {
    text-align: center;
    font-size: xx-large;
    /* margin-bottom: 30px; */
}
.p_content {
    text-align: center;
    font-size: large;
    color: #757575;
    /* margin-top: 30px; */
    position: relative;
    max-width: 500px;
    margin: 0 auto;
    margin-top: 9px;
}
.p_action {
    float: right;
    margin-top: 18px;

}
</style>
