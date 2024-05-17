<?php
function layout_adminaccordion($db)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $queue_id = $_POST['queue_id'];
        $db->removeStudent($queue_id);
        header("location: admin");
    }
    $classrooms = $db->getClassroomDatabase()->getClassrooms();

    foreach ($classrooms as $classroom) {

        ?>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        <?php echo $classroom['classroom_name']; ?>
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table>
                            <?php
                            foreach ($db->getUserByClassroomId($classroom['id']) as $user) {
                                ?>

                                <tr>
                                    <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
                                    <td><?php echo $user['username'] ?></td>

                                    <form method="post">
                                        <input type="hidden" name="queue_id" value="<?php echo $user['id'] ?>">
                                        <button type="submit" class="btn btn-success">Mark as done</button>
                                    </form>
                                    </td>


                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                    </div>
                </div>
            </div>

        </div>

        <?php
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?php

}