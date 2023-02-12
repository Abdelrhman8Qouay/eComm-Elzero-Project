<?php

    /*
    ===================================================
    == Manage Comments Page
    == You Can Edit | Delete | Approve Comments From Here
    ===================================================
    */

    session_start();

    $pageTitle = 'Comments';

    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard | Members';

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Members Page

            // If You Want To Show Your Self In Table As Admin.. Don't Make (WHERE Group ....)
            // Select All Users Except Admin
            $stmt = $con->prepare("SELECT
                                        comments.*, items.Name AS Item_Name, users.Username AS Member
                                    FROM
                                        comments
                                    INNER JOIN
                                        items
                                    ON
                                        items.Item_ID = comments.item_id
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.user_id");
            $stmt->execute();
            // Assign To Variable
            $rows = $stmt->fetchAll();

            ?>
            <div class="container my-3">
                <h1 class="text-center">Manage Comments</h1>

                <table class="main-table table table-dark table-striped">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Item Name</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Added Data</th>
                            <th scope="col">Control</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <th><?php echo $row['c_id'] ?></th>
                            <th><?php echo $row['comment'] ?></th>
                            <th><?php echo $row['Item_Name'] ?></th>
                            <th><?php echo $row['Member'] ?></th>
                            <th><?php echo $row['comment_date'] ?></th>
                            <th><?php echo '
                                <a href="comments.php?do=Edit&comid=' . $row['c_id'] .' " class="btn btn-info"><span class="material-symbols-outlined">edit_note</span>Edit</a>
                                <a href="comments.php?do=Delete&comid=' . $row['c_id'] .' " class="btn btn-danger confirma-message"><span class="material-symbols-outlined">close</span>Delete</a>
                                ' ?></th>
                            <th><?php if ( $row['status'] == 0) { echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] .' " class="btn btn-primary">Approve</a>'; } ?></th>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php

        }  elseif ($do == 'Edit') { //Edit Page

            // Check If Get Request comid Is Numeric & Get The Integer Value Of It
            $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? ");

            // Execute Query
            $stmt->execute(array($comid));
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count If Exists with This ID
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) {

                ?>
                <div class="container my-3">
                    <h1 class="text-center">Edit Comment</h1>

                    <form class="row g-3 needs-validation" action="?do=Update" method="POST">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                        <div class="mb-3">
                            <label for="username" class="form-label">Comment</label>
                            <textarea name="comment" id="comment" class="form-control"><?php echo $row['comment'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>

            <?php
            } else { // If There's No Such ID Show The Error Message
                $theMsg = '<div class="container my-3">
                        <div class="alert alert-danger" role="alert">
                            There\'s No Such ID For User Id
                        </div>
                    </div>
                ';
                redirectHome($theMsg, 'back', 5);
            }
        } elseif ($do == 'Update') { // Update Page
            echo '<h1 class="text-center text-dark">Update Comment</h1>';
            echo '<div class="container my-3">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get The Variables From The Form
                $comid  = $_POST['comid'];
                $comment  = $_POST['comment'];

                // Update The Database With This Info

                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>{$stmt->rowCount()} Record Updated</div>";
                redirectHome($theMsg, 'back', 5);

            } else {
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Update Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

            echo '</div>';
        } elseif ($do == 'Delete') { // Delete Comment Page
            echo '<h1 class="text-center">Delete Comment</h1>';
            echo '<h1 class="container">';

            // Check If Get Request comid Is Numeric & Get The Integer Value Of It
            $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            // The Comment Have No Name To Get It
            // $name = getOneWithID('Username', 'users', $userid, 'UserID');

            // Select All Data Depend On This ID
            $check = checkItem('c_id', 'comments', $comid);

            // If There's Such ID Show The Form
            if ($check > 0) {
                // Delete The Member From Database
                $stmt = $con->prepare('DELETE FROM comments WHERE c_id = :zid');
                //
                $stmt->bindParam(':zid', $comid);
                $stmt->execute();

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>The Comment Is Deleted</div>";
                redirectHome($theMsg, 'back', 5);

            } else { // can't see this page
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Delete Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

            echo '</div>';
        } elseif ($do == 'Approve') {
            echo '<h1 class="text-center">Approve Comment</h1>';
            echo '<h1 class="container">';

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It
            $comid =  isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            // Select All Data Depend On This ID
            $check = checkItem('c_id', 'comments', $comid);

            // If There's Such ID Show The Form
            if ($check > 0) {
                // Delete The Member From Database
                $stmt = $con->prepare('UPDATE comments SET `status` = 1 WHERE c_id = ?');
                //
                $stmt->execute(array($comid));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>This Comment Now Is Approved</div>";
                redirectHome($theMsg, 'back', 5);

            } else { // can't see this page
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Delete Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

            echo '</div>';
        }

        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }