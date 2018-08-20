<?php

require_once("asset.php");
?>
<script>
$.ajax({
type: "POST",
url: "asset.php",
success: function() {
console.log("TANPA DATA");
}
});
</script>
