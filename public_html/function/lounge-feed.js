var found;
function showLoungePost(start, limit, msg, append, endAppend = null, filter = null, filterValue = null) {
  // start ajax call to get the lounge list, if exists
  var data = {lounge : "", start : start, limit : limit, msg : msg};
  if(filter != null) {
    data = {lounge : "", start : start, limit : limit, msg : msg, filter : filter, filterValue : filterValue};
    console.log("SADDDDDDDDDOIJASDOJSAOD");
  } else {
    console.log("SADASD" + filter + filterValue);
  }
  $.ajax({
    type: "POST",
    url: "http://indomotorart.com/function/lounge-feed.php",
    data: data,
    success: function(res) {
      console.log(res);
      // if server doesn't retries "notfound", then it means there is one or
      // more lounge posts
      if(res != "notfound") {
        res = JSON.parse(res);
        found = 1;
        for(var i = 0; i < res.length; i++) {
          var newLounge = '<div class="lounge-item lounge-top" id="post-' + res[i].ID +'">'
          + '<div class="userinfo">'
          + '<div class="useravatar">'
          + res[i].avatar
          + '</div>'
          + '<div class="userdetails">'
          + res[i].usernick + '<br><small>pada ' + res[i].date + '</small>'
          + '</div>'
          + '</div>'
          + '<div class="lounge-content">'
          + res[i].content
          + '</div>'
          + '<div class="lounge-actions-' + i + '">'
          + '<small><a class="comment-btn" data-username="' + res[i].username + '" data-comment-id="' + res[i].ID +'">Comment</a> . <a href="http://indomotorart.com/lounge/showpost.php?id=' + res[i].ID + '">Share link</a>  </small>'
          + '</div>'
          + '</div>';

          // append each of lounge post to the place
          $("" + append).append(newLounge);
          // detect if the post has childs or not
          if(res[i].childs.length > 0) {
            // then it does
            $("#post-" + res[i].ID).append("<small><a class='fa fa-angle-down sc' data-comment-id='" + res[i].ID + "'>Show comments...</a></small>");
          } else {
            $("#post-" + res[i].ID).append("<small><a class='fa fa-angle-up sc' style='display:none' id='show-" + res[i].ID + "' data-comment-id='" + res[i].ID + "'>Close comments...</a></small>");
          }
          // append the place for lounge comments should go
          $("#post-" + res[i].ID).append("<div class='lounge-comments' id='lounge-comments-" + res[i].ID + "'></div>");
          // get each of lounge comments if exists
          for(var j = 0; j < res[i].childs.length; j++) {
            console.log(j);
            newLounge = '<div class="lounge-item lounge-comment lounge-comment-' + res[i].ID + '" id="post-' + res[i].childs[j].ID +'">'
            + '<div class="userinfo">'
            + '<div class="useravatar">'
            + res[i].childs[j].avatar
            + '</div>'
            + '<div class="userdetails">'
            + res[i].childs[j].usernick + '<br><small>pada ' + res[i].childs[j].date + '</small>'
            + '</div>'
            + '</div>'
            + '<div class="lounge-content">'
            + res[i].childs[j].content
            + '</div>'
            + '<div class="lounge-actions">'
            + '<small><a class="comment-btn reply" data-username="' + res[i].childs[j].username + '" data-comment-id="' + res[i].ID +'">Reply</a> . <a href="http://indomotorart.com/lounge/showpost.php?id=' + res[i].ID + '">Share link</a>  </small>'
            + '</div>'
            + '</div>';
            // append each of lounge comment to the place
            $("#lounge-comments-" + res[i].ID).append(newLounge);
          }
        }
      } else {
        // if not found
        console.log("notfound" + res);
        found = 0;
        // add msg to the end
        if(endAppend != null) {
          $("#" + endAppend).html(msg);
          $("#" + endAppend).fadeIn("fast");
        }
      }
    }
  })
}
