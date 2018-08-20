<?php require_once('../asset.php'); ?>
<script type="text/javascript">

// from https://stackoverflow.com/questions/9196954/ by ShankarSangoli (https://stackoverflow.com/users/772055/shankarsangoli)

function parse() {
  console.log("ADASD");
  var lines = $('#from').val().split('\n');
  for(var i = 0; i < lines.length;i++) {
    var node = document.createTextNode("<option value='" + lines[i] + "'>" + lines[i] + "</option>\n");
    document.getElementById("to").appendChild(node);
  }
}
</script>
<textarea id="from">

</textarea>
<br>
<br>
to
<br>
<br>
<textarea id="to">

</textarea>

<button onclick="parse()" value="PARSE">PARSE</button>
<style>
textarea {
  width: 100%;
  min-height: 200px;
}
</style>
