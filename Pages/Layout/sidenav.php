<?php

function layout_sidenav($db)
{

    if (!$db->getUsersDatabase()->getAuth()->isLoggedIn()) {
        header("Location: /login");
    }

    ?>
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index: 3; width: 300px" id="mySidebar">
        <br />
        <div class="w3-container w3-row">
            <div class="w3-col s8 w3-bar">
                <span>Welcome, <strong><?php echo $db->getUsersDatabase()->getAuth()->getUsername() ?></strong></span><br />
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i></a>
                <a href="#" class="w3-bar-item w3-button"><i class="fa fa-cog"></i></a>
            </div>
        </div>
        <hr />
        <div class="w3-container">
            <h5>Dashboard</h5>
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black"
                onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
            <?php foreach ($db->getUserClassrooms($db->getUsersDatabase()->getAuth()->getId()) as $classroom) { ?>
                <a href="/classroom?id=<?php echo $classroom['classroom_id'] ?>" class="w3-bar-item w3-button w3-padding"><i
                        class="fa fa-users fa-fw"></i>  <?php echo $classroom['classroom_name'] ?></a>
            <?php } ?>
            <br /><br />
        </div>
    </nav>
    <?php
}

