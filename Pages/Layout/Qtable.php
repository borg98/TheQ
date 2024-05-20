<?php

function layout_Qtable($classroom_id)
{

    $db = new DB();
    $userRole = 16;

    if ($db->checkUserRole($db->getUsersDatabase()->getAuth()->getUserId(), $classroom_id) == 1) {

        global $userRole;
        $userRole = 1;
    }
    if ($db->checkUserRole($db->getUsersDatabase()->getAuth()->getUserId(), $classroom_id) == 2) {
        global $userRole;
        $userRole = 2;
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['queue_id'])) {


            $queue_id = $_POST['queue_id'];
            $db->markQueueAsDone($queue_id);
        }
        if (isset($_POST['user_id']) && isset($_POST['classroom_id'])) {
            $user_id = $_POST['user_id'];
            $classroom_id = $_POST['classroom_id'];
            $db->removeStudent($user_id, $classroom_id);
        }
        header("location: classroom?id=$classroom_id");

    }
    ?>

    <div class="w3-panel">
        <div class="w3-row-padding" style="margin: 0 -16px">

            <div class="w3-container">
                <h5>Queue</h5>
                <table class="w3-table w3-striped w3-white">
                    <?php
                    if ($db->checkIfAlreadyInQueue($classroom_id, $db->getUsersDatabase()->getAuth()->getUserId())) {

                        ?>
                        <p>Already in queue</p>

                        <?php
                    }

                    foreach ($db->getClassroomQueueById($classroom_id) as $queue) {

                        $user = $db->getUserById($queue['user_id']);
                        if (($db->getUsersDatabase()->getAuth()->getUserId()) == $queue['user_id']) {
                            ?>

                            <tr>

                                <td class="table-column"><i
                                        class="fa fa-user w3-text-blue w3-large"></i><?php echo $db->getUsersDatabase()->getAuth()->getUsername() ?>
                                </td>
                                <td class="table-column"><i><?php echo date('Y-m-d H:i:s') ?></i></td>
                                <td class="table-column">
                                    <form method="post">
                                        <input type="hidden" name="queue_id" value="<?php echo $queue['id'] ?>">
                                        <input type="hidden" name="classroom_id" value="<?php echo $classroom_id ?>">
                                        <input type="hidden" name="user_id"
                                            value="<?php echo $db->getUsersDatabase()->getAuth()->getUserId() ?>">

                                        <button type="submit" class="btn btn-danger">Leave queue</button>
                                    </form>
                                </td>

                                <?php
                        } else {






                            ?>

                                <td class="table-column"><i class="fa fa-user w3-text-blue w3-large"></i></td>
                                <td class="table-column"><?php echo $user['username'] ?></td>
                                <td class="table-column"><i><?php echo $queue['created_at'] ?></i></td>

                                <?php
                        }
                        if ($userRole == 1 || $userRole == 2) {
                            ?>
                                <td class="table-column"> <?php echo $queue['question'] ?></td>
                                <td class="table-column">

                                    <?php echo $queue['studentlocation'] ?>

                                <td class="table-column">

                                    <form method="post">
                                        <input type="hidden" name="queue_id" value="<?php echo $queue['id'] ?>">
                                        <button type="submit" class="btn btn-success">Mark as done</button>
                                    </form>
                                </td>



                                <?php
                        }

                        ?>
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