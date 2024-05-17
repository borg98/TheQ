<?php

function layout_Qtable($classroom_id)
{

    $db = new DB();
    $user_roleIsAdmin = $db->getUsersDatabase()->getAuth()->hasRole(\Delight\Auth\Role::ADMIN);
    $user_roleIsAuthor = $db->getUsersDatabase()->getAuth()->hasRole(\Delight\Auth\Role::AUTHOR);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $queue_id = $_POST['queue_id'];
        $db->markQueueAsDone($queue_id);
        header("location: classroom?id=$classroom_id");
    }
    ?>

    <div class="w3-panel">
        <div class="w3-row-padding" style="margin: 0 -16px">

            <div class="w3-container">
                <h5>Queue</h5>
                <table class="w3-table w3-striped w3-white">
                    <?php

                    foreach ($db->getClassroomQueueById($classroom_id) as $queue) {

                        $user = $db->getUserById($queue['user_id']);
                        ?>
                        <tr>
                            <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
                            <td><?php echo $user['username'] ?></td>
                            <td><i><?php echo $queue['created_at'] ?></i></td>

                            <?php
                            if ($user_roleIsAdmin || $user_roleIsAuthor) {
                                ?>
                                <td> <?php echo $queue['question'] ?></td>
                                <td>

                                    <?php echo $queue['studentlocation'] ?>

                                <td>

                                    <form method="post">
                                        <input type="hidden" name="queue_id" value="<?php echo $queue['id'] ?>">
                                        <button type="submit" class="btn btn-success">Mark as done</button>
                                    </form>
                                </td>


                            </tr>

                            <?php
                            }
                    }
                    ?>


                </table>
            </div>
        </div>
    </div>
    <?php
}