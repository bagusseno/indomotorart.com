<header class="common-header mid-header" id="mid-header">

  <div class="container">
    <div id="main-main">
      <div id="main-box">
        <div id="search-box" class="mb">
          <form class="search-form" method="get" action="http://indomotorart.com/search.php">
            <input type="text" id="search-input" name="keyword" placeholder="Cari sesuatu..."/>
            <button type="submit" id="search-submit"><a class="fa fa-search"></a></button>
            </form>
        </div>
      </div>
    </div>

    <div id="menu-list-2" class="mb">
      <span><a href="javascript:void(0)">Daftar Motor <i class="fa fa-caret-down"></i></a></span>
    </div>

  </div>

</header>
<div class="vertical-tab-menu">
    <ul style="display: grid;">
    <?php
        $brands = $conn->query("SELECT name FROM brand");
        $types = $conn->query("SELECT name, brandID FROM tipe");
        $brands = $brands->fetch_all(MYSQLI_ASSOC);
        $types = $types->fetch_all(MYSQLI_ASSOC);
        foreach($brands as $key => $b) {
            $brand = str_replace(" ", "-", $b["name"]);
            echo "<a href='http://indomotorart.com/motor/$brand'><li class='brand' data-id='$key'>" . ucwords($b["name"]) . "</li></a>";
        }
    ?>
    </ul>
</div>
<header class="common-header mid-header">
</header>