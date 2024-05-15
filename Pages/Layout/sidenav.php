<?php



function layout_sidenav($dbContext)
{

    ?>
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index: 3; width: 300px" id="mySidebar">
        <br />
        <div class="w3-container w3-row">
            <div class="w3-col s4">
                <img src="/w3images/avatar2.png" class="w3-circle w3-margin-right" style="width: 46px" />
            </div>
            <div class="container">
                <ul class="header-links pull-left">
                    <li>
                        <a href="#"><i class="fa fa-phone"></i> +46-111-22-33</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-envelope-o"></i> <?php
                        echo $dbContext->getUsersDatabase()->getAuth()->getUsername() ?>

                        </a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-map-marker"></i> Testgatan 122</a>
                    </li>
                </ul>
                <ul class="header-links pull-right">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="" title="Manage">Manage</a>
                        </li>
                        <?php
                        if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="/accountlogout.php">Logout</a>
                            </li>
                        <?php }
                        ?>
                        <?php
                        if (!$dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="/register.php">Register</a>
                            </li>
                        <?php }
                        ?>

                        <?php
                        if (!$dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="/accountlogin.php">Login</a>
                            </li>
                        <?php }
                        ?>
                    </ul>
                </ul>
            </div>
        </div>
        <hr />
        <div class="w3-container">
            <h5>Dashboard</h5>
        </div>

        <div class="w3-bar-block">
            <?php if (!$dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                <p class="fa fa-fw">Log in to see classrooms</p>
            <?php } else { ?>
                <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black"
                    onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i> Close Menu</a>
                <?php foreach ($dbContext->getUserClassrooms($dbContext->getUsersDatabase()->getAuth()->getUserId()) as $classroom) { ?>
                    <a href="/classroom?id=<?php echo $classroom['classroom_id'] ?>" class="w3-bar-item w3-button w3-padding"><i
                            class="fa fa-users fa-fw"></i> <?php echo $classroom['classroom_name'] ?></a>
                <?php }
            } ?>
            <br /><br />
        </div>
    </nav>
    <?php
}

