<textarea id="from"></textarea>
<br><br>
to
<br><br>
<textarea id="to"></textarea>
<button onclick="grab()" value="GRAB">GRAB</button>
<style>
textarea {
  width: 100%;
  height: 200px;
}
</style>
<?php require_once('../asset.php'); ?>

<script>

function grab() {
  txt1 = $("#from").val();
  txt2 = $("#to").val();

  var lines = $('#from').val().split('\n');
  for(var i = 0; i < lines.length;i++) {
    lines[i] = lines[i].replace(/\t/g, "");

    var node = document.createTextNode(lines[i] + "\n");
    document.getElementById("to").appendChild(node);
  }
}
</script>
