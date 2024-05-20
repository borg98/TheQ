<?php


function layout_Comments()
{
    $db = new DB();
    $Questions = $db->getRecentQuestions();

    ?>

    <!DOCTYPE html>
    <html>

    <div class="w3-container">
        <h5>Recent Comments (Last 24 hours)</h5>
        <div class="w3-row">

            <div class="w3-col m10 w3-container">
                <?php
                foreach ($Questions as $question) {
                    ?>
                    <h4>
                        classroom - <?php echo $question['classroom_id'] ?>
                        <span class="w3-opacity w3-medium"><?php echo $question['created_at'] ?></span>
                    </h4>
                    <p>
                        <?php echo $question['question'] ?>
                    </p>
                    <br />
                    <?php
                }
                ?>

            </div>
        </div>


    </div>
    <?php
}

