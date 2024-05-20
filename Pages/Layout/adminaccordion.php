<?php
function layout_adminaccordion($db)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['selectedClassroom'])) {
            $user_id = $_POST['user_id'];
            $classroom_id = $_POST['selectedClassroom'];
            $db->addUserToClassroom($user_id, $classroom_id);
        }
        if (isset($_POST['status']) && isset($_POST['user_id'])) {
            $status = $_POST['status'];
            $user_id = $_POST['user_id'];
            $db->updateUserStatus($user_id, $status);

        }
        if (isset($_POST['removedStudent']) && isset($_POST['classroom_id'])) {
            $removedStudent = $_POST['removedStudent'];
            $classroom_id = $_POST['classroom_id'];
            $db->removeStudentFromClassroom($removedStudent, $classroom_id);
            $db->removeStudent($removedStudent, $classroom_id);
        }
        if (isset($_POST['classroomName']) && isset($_POST['description'])) {
            $classroomName = $_POST['classroomName'];
            $description = $_POST['description'];
            $db->addClassroom($classroomName, $description);
        }
        if (isset($_POST['role']) && isset($_POST['user_id']) && isset($_POST['classroom_id'])) {
            $role = $_POST['role'];
            $user_id = $_POST['user_id'];
            $classroom_id = $_POST['classroom_id'];
            $db->SetUserRole($user_id, $role, $classroom_id);
        }


        header("location: /admin");
        exit;

    }
    $classrooms = $db->getAllClassrooms();
    ?>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hej">
        Add classroom
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
                        <input type="text" name="classroomName" placeholder="name of the classroom">
                        <input type="text" name="description" placeholder="description">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php

    foreach ($classrooms as $classroom) {

        ?>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne<?php echo $classroom['id'] ?>" aria-expanded="true"
                        aria-controls="collapseOne">
                        <?php echo $classroom['classroom_name'], "     -      ", $classroom['description']; ?>
                    </button>
                </h2>
                <div id="collapseOne<?php echo $classroom['id'] ?>" class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table>
                            <?php
                            foreach ($db->getUserByClassroomId($classroom['id']) as $user) {
                                ?>

                                <tr>
                                    <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
                                    <td><?php echo $user['username'] ?></td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="classroom_id" value="<?php echo $classroom['id'] ?>">
                                            <input type="hidden" name="removedStudent" value="<?php echo $user['id'] ?>">
                                            <button type="submit" class="btn btn-success">-</button>

                                        </form>
                                    </td>
                                    <td>

                                        <form method="post">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                                            <input type="hidden" name="classroom_id" value="<?php echo $classroom['id'] ?>">
                                            <?php
                                            // Assume $id is defined elsewhere
                                            $statusRole = $db->checkUserRole($user['id'], $classroom['id']);

                                            ?>
                                            <select name='role'>

                                                <option disabled>Select status</option>
                                                <option value="1" <?php if ($statusRole == 1)
                                                    echo 'selected'; ?>>Admin</option>
                                                <option value="2" <?php if ($statusRole == 2)
                                                    echo 'selected'; ?>>Teaher</option>
                                                <option value="16" <?php if ($statusRole == 16)
                                                    echo 'selected'; ?>>Student</option>

                                            </select>
                                            <button type="submit" class="btn btn-success"> give role</button>

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
    <hr />
    <hr />
    <hr />

    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers"
                    aria-expanded="true" aria-controls="collapseOne">
                    All Users
                </button>

            </h2>
            <div id="collapseUsers" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table>
                        <?php
                        foreach ($db->getallUsers($classroom['id']) as $user) {
                            ?>

                            <tr>
                                <td><i class="fa fa-user w3-text-blue w3-large"></i></td>
                                <td><?php echo $user['username'] ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                                        <?php
                                        // Assume $id is defined elsewhere
                                        $status = $db->checkUserStatus($user['id']);
                                        ?>

                                        <select name='status'>
                                            <option disabled>Select status</option>
                                            <option value="0" <?php if ($status == 0)
                                                echo 'selected'; ?>>Nomral</option>
                                            <option value="1" <?php if ($status == 1)
                                                echo 'selected'; ?>>Archived</option>
                                            <option value="2" <?php if ($status == 2)
                                                echo 'selected'; ?>>Banned</option>
                                            <option value="5" <?php if ($status == 5)
                                                echo 'selected'; ?>>Suspended</option>
                                        </select>

                                        <select name='selectedClassroom'>
                                            <option selected disabled>Select classroom</option>
                                            <?php
                                            foreach ($classrooms as $classroom) {
                                                ?>
                                                <option value="<?php echo $classroom['id'] ?>">
                                                    <?php echo $classroom['classroom_name'] ?>
                                                </option>
                                                <?php
                                            }
                                            ?>



                                        </select>
                                        <button type="submit" class="btn btn-success">+</button>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?php

}