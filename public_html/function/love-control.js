// from StackOverFlow by Majid Golshadi https://stackoverflow.com/users/2603921/majid-golshadi the link:https://stackoverflow.com/a/20819663
$(document).on('click', '.love-post', function() {

  console.log("Clicked");
  var postID = $(this).attr('data-loveid');
  var thisx = $(this);
  var loggedin = <?php echo $is_user_logged_in ?>;
  var activated = <?php echo $is_user_activated ?>;

  console.log(postID);
  if(loggedin == 0) {
    window.location.replace("http://indomotorart.com/login");
  } else if(activated == 0) {
    window.location.replace("http://indomotorart.com/akun/verifikasi");
  } else {
      $.ajax({
        type: "POST",
        url: "http://indomotorart.com/function/vote.php",
        dataType: "JSON",
        data: {postID : postID},
        success: function(r) {
          console.log(r);
          if(r == "unlogged") {
            window.location.replace("http://indomotorart.com/login");
          }
          if(r["updown"] == '1') {
            var total= r["total"];
            thisx.addClass("loved fa-heart");
            thisx.removeClass("fa-heart-o");
            thisx.html("" + total);
          } else if (r["updown"] == '-1') {
            var total= r["total"];
            thisx.removeClass("loved fa-heart");
            thisx.addClass("fa-heart-o");
            thisx.html("" + total);
          } else {
            alert("terjadi error");
          }
        }
      })
  }
})
$(document).on("dblclick", ".post-item", function() {
  console.log("ASDS")
  $(this).children('.bottom-post').children('.love-post').click();
  console.log("dblclicked");
});
