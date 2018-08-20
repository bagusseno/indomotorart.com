<?php

// a function to cache the external files

if(isset($_GET["url"]) && isset($_GET["drt"])) {
  header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', times() + (60 * 60) * $_GET['duration']));
}

echo file_get_contents($_GET["url"]);

?>
