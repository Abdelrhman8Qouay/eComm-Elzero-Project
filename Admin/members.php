<?php

    /*
    ===================================================
    == Manage Members Page
    == You Can Add | Edit | Delete Members From Here
    ===================================================
    */

    session_start();

    $pageTitle = 'Members';

    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard | Home';

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Members Page

            if($check == 1):
                echo 'Exist Member';
            endif;

            // If You Want To Show Your Self In Table As Admin.. Don't Make (WHERE Group ....)
            // Select All Users Except Admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 2");
            $stmt->execute();
            // Assign To Variable
            $rows = $stmt->fetchAll();

            ?>
            <div class="container my-3">
                <h1 class="text-center text-dark">Manage Members</h1>

                <div class="mb-3">
                    <a href="members.php?do=Add" class="btn btn-primary"><span class="material-symbols-outlined">add</span> Add Member</a>
                </div>
                <table class="main-table table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">FullName</th>
                            <th scope="col">Registered Data</th>
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <th><?php echo $row['UserID'] ?></th>
                            <th><?php echo $row['Username'] ?></th>
                            <th><?php echo $row['Email'] ?></th>
                            <th><?php echo $row['FullName'] ?></th>
                            <th></th>
                            <th><?php echo '
                                <a href="members.php?do=Edit&userid=' . $row['UserID'] .' " class="btn btn-info"><span class="material-symbols-outlined">edit_note</span>Edit</a>
                                <a href="members.php?do=Delete&userid=' . $row['UserID'] .' " class="btn btn-danger confirma-message"><span class="material-symbols-outlined">close</span>Delete</a>
                                ' ?></th>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="self_modal" id="confirm-delete">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            Are You Sure You Want Delete Member?
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" id="close-btn">Close</button>
                        <button type="button" class="btn btn-danger" id="yes-btn">Yes</button>
                    </div>
                </div>
            </div>

            <?php

        } elseif ($do == 'Add') { // Add Members Page

            ?>
            <div class="container my-3">
                <h1 class="text-center text-dark">Add Member</h1>

                <form class="row g-3 needs-validation" action="?do=Insert" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username To Login Into Shop" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="full" class="form-label">FullName</label>
                        <input type="text" class="form-control" name="full" id="full" placeholder="Full Name In Your Profile Page" required>
                    </div>
                    <div class="mb-3">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            <?php

        } elseif ($do == 'Insert') { // Insert Page
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo '<h1 class="text-center text-dark">Insert Member</h1>';
                echo '<div class="container my-3">';

                // get The Variables From The Form
                $user  = $_POST['username'];
                $pass  = $_POST['password'];
                $email  = $_POST['email'];
                $name  = $_POST['full'];

                $hashPass = sha1($_POST['password']);

                // Validate The Form

                $formErrors = array();

                if(empty($user)) {
                    $formErrors[] = 'Username Can\'t Be Empty';
                }
                if(strlen($user) < 4) {
                    $formErrors[] = 'Username Can\'t Be Less Than 4 Characters';
                }
                if(strlen($user) > 20) {
                    $formErrors[] = 'Username Can\'t Be More Than 20 Characters';
                }
                if(empty($pass)) {
                    $formErrors[] = 'Password Can\'t Be Empty';
                }
                if(empty($name)) {
                    $formErrors[] = 'FullName Can\'t Be Empty';
                }
                if(empty($email)) {
                    $formErrors[] = 'Email Can\'t Be Empty';
                }
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $formErrors[] = 'Email Is Not Valid Like As Good Email';
                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                // Check If There's No Error Proceed The Update Operation
                if(empty($formErrors)) {
                    //Check If User Exist In Database
                    $check = checkItem("Username", "users", $user);

                    if($check == 1) {
                        redirectHome('Sorry This Username Is Already Exist', 5);
                    } else {

                        // Insert User Info In Database
                        $stmt = $con->prepare("INSERT INTO
                                                    users(Username, Password, Email, FullName)
                                                VALUES(:user, :pass, :email, :zname) ");
                        $stmt->execute(array('user' => $user, 'pass' => $hashPass, 'email' => $email, 'zname' => $name));

                        // Echo Success Message
                        echo  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . ' Record Insert</div>';
                    }
                }

                echo '</div>';
            } else {
                // You Cant See this Page

                redirectHome('Error Update Page: Sorry You Can\'t Browse This Page Directly', 6);
            }

        } elseif ($do == 'Edit') { //Edit Page

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It
            $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

            // Execute Query
            $stmt->execute(array($userid));
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count If Exists with This ID
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) {

                ?>
                <div class="container my-3">
                    <h1 class="text-center text-dark">Edit Member</h1>

                    <form class="row g-3 needs-validation" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?php echo $row['Username'] ?>" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                            <input type="password" class="form-control" name="newpassword" id="password" autocomplete="new-password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['Email'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="full" class="form-label">FullName</label>
                            <input type="text" class="form-control" name="full" id="full" value="<?php echo $row['FullName'] ?>" required>
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
                echo '<div class="container my-3">
                        <div class="alert alert-danger" role="alert">
                            There\'s No Such ID For User Id
                        </div>
                    </div>
                ';
            }
        } elseif ($do == 'Update') { // Update Page
            echo '<h1 class="text-center text-dark">Update Member</h1>';
            echo '<div class="container my-3">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get The Variables From The Form
                $id  = $_POST['userid'];
                $user  = $_POST['username'];
                $email  = $_POST['email'];
                $name  = $_POST['full'];

                // Password Trick
                // Condition ? True : False
                $pass = empty($_POST['newpassword']) ? $pass = $_POST['newpassword'] : $pass = sha1($_POST['newpassword']);

                // Validate The Form

                $formErrors = array();

                if(empty($user)) {
                    $formErrors[] = 'Username Cant Be Empty';
                }
                if(strlen($user) < 4) {
                    $formErrors[] = 'Username Cant Be Less Than 4 Characters';
                }
                if(strlen($user) > 20) {
                    $formErrors[] = 'Username Cant Be More Than 20 Characters';
                }
                if(empty($name)) {
                    $formErrors[] = 'FullName Cant Be Empty';
                }
                if(empty($email)) {
                    $formErrors[] = 'Email Cant Be Empty';
                }
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $formErrors[] = 'Email Is Not Valid Like As Good Email';
                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                // Check If There's No Error Proceed The Update Operation
                if(empty($formErrors)) {
                    // Update The Database With This Info

                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?,  FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    // Echo Success Message
                    echo  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . ' Record Updated</div>';
                }

            } else {
                redirectHome('Error Insert Page: Sorry You Can\'t Browse This Page Directly', 6);
            }

            echo '</div>';
        } elseif ($do == 'Delete') { // Delete Member Page

            // Check If Get Request userid Is Numeric & Get The Integer Value Of It
            $userid =  isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

            // Execute Query
            $stmt->execute(array($userid));

            // The Row Count If Exists with This ID
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) {
                // Delete The Member From Database
                $stmt = $con->prepare('DELETE FROM users WHERE UserID = :user');
                //
                $stmt->bindParam(':user', $userid);
                $stmt->execute();

                // Echo Success Message
                echo  '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . ' Record Deleted</div>';

            } else {
                redirectHome('Member Not Exist, Go Home..', 5);
            }
        }

        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }