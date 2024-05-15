<?php

function layout_Qtable($classroom_id)
{

    $db = new DB();
    ?>

    <div class="w3-panel">
        <div class="w3-row-padding" style="margin: 0 -16px">

            <div class="w3-container">
                <h5>Queue</h5>
                <table class="w3-table w3-striped w3-white">
                    <?php
                    foreach ($db->getClassroomQueueById($classroom_id) as $queue) {
                        ?>
                        <tr>
                            <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
                            <td><?php echo $queue['user_id'] ?></td>
                            <td><i><?php echo $queue['time'] ?></i></td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
    <?php
}

