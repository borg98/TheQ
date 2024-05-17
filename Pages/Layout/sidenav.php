<?php



function layout_sidenav($dbContext)
{
    $username = $dbContext->getUsersDatabase()->getAuth()->getUsername();

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Styled Sidebar</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .sidebar-header {
                padding: 16px;
                text-align: center;
            }

            .sidebar-header img {
                width: 30px;
            }

            .sidebar-content ul {
                list-style-type: none;
                padding: 0;
            }

            .sidebar-content li {
                padding: 8px 0;
            }

            .sidebar-content a {
                text-decoration: none;
                color: inherit;

            }

            .sidebar-content a:hover {
                text-decoration: underline;
            }

            .sidebar-footer {
                padding: 16px;
                text-align: center;
            }

            .sidebar-footer p {
                margin: 0;
            }

            .register-container,
            .login-container {
                margin-bottom: 10px;
            }

            .register-container {
                background-color: orange;
                border: 2px solid salmon;
                border-radius: 10px;
                padding: 10px;
            }

            .login-container {
                background-color: orange;
                border: 2px solid salmon;
                border-radius: 10px;
                padding: 10px;
            }
        </style>
    </head>

    <body>

        <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index: 3; width: 300px" id="mySidebar">
            <div class="sidebar-header w3-container">
                <div class="w3-row">

                    <div class="w3-col s8">
                        <ul class="header-links">
                            <a href="#"><i class="fa fa-phone"></i> +46-111-22-33</a>
                            <a href="#"><i class="fa fa-envelope-o"></i>
                                <?php echo htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?></a>

                            <a href="#"><i class="fa fa-map-marker"></i> Testgatan 122</a>
                            <?php
                            if ($dbContext->getUsersDatabase()->getAuth()->hasRole(\Delight\Auth\Role::ADMIN)) {

                                ?>
                                <a href="/admin"><i class="fa fa-user fa-fw"></i> Admin</a>
                                <?php
                            }
                            ?>

                        </ul>
                    </div>
                </div>
            </div>
            <hr />
            <div class="sidebar-footer w3-container">
                <div class="navbar-nav text-center">
                    <?php if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                        <div class="nav-item">
                            <a class="nav-link text-dark border border-dark rounded font-weight-bold p-2"
                                href="/accountlogout.php">Logout</a>
                        </div>
                    <?php } else { ?>
                        <div class="nav-item">
                            <div class="register-container">
                                <a class="nav-link btn btn-primary rounded-pill font-weight-bold p-2"
                                    href="/register.php"><b>Register new account</b></a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <div class="login-container">
                                <a class="nav-link btn btn-outline-primary rounded-pill font-weight-bold p-2"
                                    href="/accountlogin.php"><b>Login</b></a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>






            <div class="w3-container">
                <h5>Dashboard</h5>
            </div>
            <div class="w3-bar-block sidebar-content">
                <?php if (!$dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) { ?>
                    <p class="fa fa-fw">Log in to see classrooms</p>
                <?php } else { ?>
                    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black"
                        onclick="w3_close()" title="close menu">
                        <i class="fa fa-remove fa-fw"></i> Close Menu
                    </a>
                    <?php
                    $classrooms = $dbContext->getUserClassrooms($dbContext->getUsersDatabase()->getAuth()->getUserId());
                    foreach ($classrooms as $classroom) {
                        ?>
                        <a href="/classroom?id=<?php echo htmlspecialchars($classroom['classroom_id'], ENT_QUOTES, 'UTF-8'); ?>"
                            class="w3-bar-item w3-button w3-padding">
                            <i class="fa fa-users fa-fw"></i>
                            <?php echo htmlspecialchars($classroom['classroom_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
            <hr />

        </nav>

    </body>

    </html>

    <?php
}

