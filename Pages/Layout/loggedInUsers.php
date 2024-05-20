<?php
require_once "Models/Database.php";



function layout_loggedInUsers()
{
    $db = new DB();
    $onlineUsers = $db->getOnlineUsers();


    ?>


    <div class="w3-container">
        <h5>Online users (last 30 minutes) :</h5>
        <ul class="w3-ul w3-card-4">
            <?php
            foreach ($onlineUsers as $user) {
                ?>
                <li class="w3-bar">
                    <div class="w3-bar-item">
                        <span class="w3-large"><?php echo $user['username'] ?></span>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
}

