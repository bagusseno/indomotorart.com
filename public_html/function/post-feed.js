console.log("Included");
var outRes;
var r;
var showPost = function(type, limit, append, msg = null, start, autoloadAppend = null, kind = null, note = null) {
  $.ajax({
    type: "POST",
    url: "http://indomotorart.com/function/post-feed.php",
    data: {type: type, limit: limit, start: start, kind: kind, note: note},
    success: function(res) {
      console.log("ajaxed");
      if(res == "notfound") {
        if(autoloadAppend != null) {
          $("#" + autoloadAppend).html(msg);
          $("#" + autoloadAppend).fadeIn("fast");
        } else {
          $("#" + append).append(msg);
        }
        outRes = 0;
        return "notfound";
      }
      outRes = 1;
      console.log(res);
      res = JSON.parse(res);
      r = res;
      for(var i = 0; i < res.length; i++) {
        var loved = "";
        if(res[i].loved == '1') {
          loved = "fa-heart loved";
        } else {
          loved = "fa-heart-o";
        }
        if(kind == "admin") {
          crud = '<span class="delete-post">' +
          '<a class="delete-post-btn post-btn" id="' + res[i].ID +'">Hapus</a>' +
          '</span>';
        } else {
          crud =
          '<span class="more-post">' +
          '<a class="more-post-btn post-btn" href="http://indomotorart.com/showpost.php?url=' + res[i].url + '">detail</a>' +
          '</span>';
        }
        var post = '<div class="post-item new" id="post-' + res[i].ID +'">' +
        '<a href="http://indomotorart.com/showpost.php?url=' + res[i].url + '">' +
        '<div class="top-post post-overlay">' +
        '<span class="posttitle"><a href="http://indomotorart.com/showpost.php?url=' + res[i].url + '">' + res[i].title + '</a></span>' +
        '<br><small><a href="http://indomotorart.com/showprofile.php?user=' + res[i].userurl + '">  ' + res[i].nickname + ' <span class="userdetails">(' + res[i].rep + ' rep)</span>' + '</a></small>' +
        '</div>' +

        '<div class="bottom-post post-overlay">' +
        '<span data-loveid="' + res[i].ID + '" class="left-action fa love-post post-icon ' + loved + '">' + res[i].love + '</span><a href="http://indomotorart.com/showpost.php?url=' + res[i].url + '#comments" class="left-action fa fa-comments-o post-icon">' + res[i].tComments + '</a>' + 

        '<span class="actions-post">' + crud +
        '</span>' +

        '</div>' +

        "<div class='post-thumb' style='background-image:url(" + res[i].thumb + ");'>" +

        "</div>" +

        "</a></div>";
        $("#" + append).append(post);
      }
      return r.length;
    }
  })

}
