<?php
require_once "Models/Database.php";
require_once "Pages/Layout/head.php";
require_once "Pages/Layout/footer.php";
require_once "Pages/Layout/sidenav.php";
require_once "Pages/Layout/dashboard.php";
require_once "Pages/Layout/Qtable.php";
require_once "Pages/Layout/loggedInUsers.php";
require_once "Pages/Layout/Comments.php";
require_once "Pages/Layout/topnav.php";

$db = new DB();



layout_head();
?>




<body class="w3-light-grey">
  <!-- Top container -->
  <?php
  layout_topnav();

  layout_sidenav();
  ?>
  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor: pointer"
    title="close side menu" id="myOverlay"></div>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main" style="margin-left: 300px; margin-top: 43px">
    <!-- Header -->
    <?php
    layout_dashboard();
    ?>
    <?php
    layout_Qtable();
    ?>
    <hr />
    <hr />
    <hr />
    <?php
    layout_loggedInUsers();
    ?>
    <hr />
    <?php
    layout_Comments();
    ?>
    <br />
    <?php
    layout_footer();
    ?>
    <!-- End page content -->
  </div>
</body>

</html>