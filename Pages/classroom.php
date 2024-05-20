<?php
ob_start();
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

$classroom_id = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['message']) && isset($_POST['location'])) {
        $message = $_POST['message'];
        $location = $_POST['location'];
        $db->addUserToQueue($classroom_id, $message, $db->getUsersDatabase()->getAuth()->getUserId(), $location);
        header("location: classroom?id=$classroom_id");
    }
}







layout_head();
?>

<body class="w3-light-grey">
    <?php

    layout_topnav();
    layout_sidenav($db);
    ?>
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor: pointer"
        title="close side menu" id="myOverlay"></div>
    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left: 300px; margin-top: 43px">
        <?php

        layout_dashboard();

        layout_Qtable($classroom_id);

        ?>


        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hej">
            Launch demo modal
        </button>
        <div class="modal" tabindex="-1" id="hej">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Get in Q</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <input type="text" name="message" placeholder="What do you need help with?">
                            <select name="location" id="locationSelect">
                                <option value="0" disabled>Location?</option>
                                <option value="School">School</option>
                                <option value="Teams">Teams</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>