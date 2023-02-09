<?php

    /*
    ===================================================
    == Items Page
    == You Can Add | Edit | Delete Items From Here
    ===================================================
    */

    session_start();

    $pageTitle = 'Items';

    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard | Members';

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Items Page

            // If You Want To Show Your Self In Table As Admin.. Don't Make (WHERE Group ....)
            // Select All Users Except Admin
            $stmt = $con->prepare("SELECT
                                        items.*,
                                        categories.Name AS category_name,
                                        users.Username
                                    FROM
                                        items
                                    INNER JOIN
                                        categories
                                    ON
                                        categories.ID = items.Cat_ID
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = items.Member_ID");
            $stmt->execute();
            // Assign To Variable
            $items = $stmt->fetchAll();

            ?>
            <div class="container my-3">
                <h1 class="text-center">Manage Items</h1>

                <div class="mb-3">
                    <a href="items.php?do=Add" class="btn btn-info"><span class="material-symbols-outlined">add</span> New Item</a>
                </div>
                <table class="main-table table table-dark table-striped">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th scope="col">Adding Date</th>
                            <th>Category</th>
                            <th>Username</th>
                            <th scope="col">Control</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach($items as $item): ?>
                        <tr>
                            <th><?php echo $item['Item_ID'] ?></th>
                            <th><?php echo $item['Name'] ?></th>
                            <th><?php echo $item['Description'] ?></th>
                            <th><?php echo $item['Price'] ?></th>
                            <th><?php echo $item['Add_Date'] ?></th>
                            <th><?php echo $item['category_name'] ?></th>
                            <th><?php echo $item['Username'] ?></th>
                            <th><?php echo '
                                <a href="items.php?do=Edit&itemid=' . $item['Item_ID'] .' " class="btn btn-info"><span class="material-symbols-outlined">edit_note</span>Edit</a>
                                <a href="items.php?do=Delete&itemid=' . $item['Item_ID'] .' " class="btn btn-danger confirma-message"><span class="material-symbols-outlined">close</span>Delete</a>
                                ' ?></th>
                            <th><?php if ( $item['Approve'] == 0) { echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] .' " class="btn btn-primary"><span class="material-symbols-outlined">done</span>Approve</a>'; } ?></th>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

            <?php

        } elseif ($do == 'Add') { // Add Items Page

            ?>
            <div class="container my-3">
                <h1 class="text-center">Add New Item</h1>

                <form class="row g-3 needs-validation" action="?do=Insert" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name Of The Item" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Description Of The Item" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" name="price" id="price" placeholder="$ Price Of The Item" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" name="country" id="country" placeholder="Country Of Made" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="member" class="form-label">Members</label>
                        <select class="form-select" name="member" id="member">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user) {
                                    echo '<option value="'. $user['UserID'] .'">' .$user['Username']. '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat) {
                                    echo '<option value="'. $cat['ID'] .'">' .$cat['Name']. '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            <?php

        } elseif ($do == 'Insert') { // Insert Page
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo '<h1 class="text-center text-dark">Insert Item</h1>';
                echo '<div class="container my-3">';

                // get The Variables From The Form
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $price  = $_POST['price'];
                $country  = $_POST['country'];
                $status  = $_POST['status'];
                $member  = $_POST['member'];
                $cate  = $_POST['category'];

                // Validate The Form
                $formErrors = array();

                if(empty($name)) {
                    $formErrors[] = 'Name Can\'t Be Empty';
                }
                if(empty($desc)) {
                    $formErrors[] = 'Description Can\'t Be Empty';
                }
                if(empty($price)) {
                    $formErrors[] = 'Price Can\'t Be Empty';
                }
                if(empty($country)) {
                    $formErrors[] = 'Country Can\'t Be Empty';
                }
                if($status == 0) {
                    $formErrors[] = 'You Must Choose The Status';
                }
                if($member == 0) {
                    $formErrors[] = 'You Must Choose The Member';
                }
                if($cate == 0) {
                    $formErrors[] = 'You Must Choose The Category';
                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                // Check If There's No Error Proceed The Update Operation
                if(empty($formErrors)) {

                    // Insert User Info In Database
                    $stmt = $con->prepare("INSERT INTO
                    items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember) ");
                    $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zprice' => $price, 'zcountry' => $country, 'zstatus' => $status, 'zcat' => $cate, 'zmember' => $member));

                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success' role='alert'>$name Is Inserted</div>";
                    redirectHome($theMsg, null, 5);

                }

                echo '</div>';
            } else { // You Can't See this Page
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Insert Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

        } elseif ($do == 'Edit') { //Edit Items Page

            // Check If Get Request item Is Numeric & Get The Integer Value Of It
            $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");

            // Execute Query
            $stmt->execute(array($itemid));
            // Fetch The Data
            $item = $stmt->fetch();
            // The Row Count If Exists with This ID
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) {

                ?>

                <div class="container my-3">
                    <h1 class="text-center">Edit Item</h1>

                    <form class="row g-3 needs-validation" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name Of The Item" value="<?php echo $item['Name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Description Of The Item" value="<?php echo $item['Description'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="price" placeholder="$ Price Of The Item" value="<?php echo $item['Price'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" name="country" id="country" placeholder="Country Of Made" value="<?php echo $item['Country_Made'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="0">...</option>
                                <option value="1" <?php if($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                                <option value="2" <?php if($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
                                <option value="3" <?php if($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                                <option value="4" <?php if($item['Status'] == 4) { echo 'selected'; } ?>>Very Old</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="member" class="form-label">Members</label>
                            <select class="form-select" name="member" id="member">
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user) {
                                        echo '<option value="'. $user['UserID'] .'"';
                                        if($item['Member_ID'] == $user['UserID']) { echo 'selected'; }
                                        echo '>' . $user['Username']. '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" name="category" id="category">
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    foreach($cats as $cat) {
                                        echo '<option value="'. $cat['ID'] .'"';
                                        if($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
                                        echo '>' .$cat['Name']. '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Item" class="btn btn-primary">
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
            echo '<h1 class="text-center text-dark">Update Item</h1>';
            echo '<div class="container my-3">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get The Variables From The Form
                $id  = $_POST['itemid'];
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $price  = $_POST['price'];
                $country  = $_POST['country'];
                $status  = $_POST['status'];
                $cate  = $_POST['category'];
                $member  = $_POST['member'];

                // Validate The Form
                $formErrors = array();

                if(empty($name)) {
                    $formErrors[] = 'Name Can\'t Be Empty';
                }
                if(strlen($name) <= 1) {
                    $formErrors[] = 'Name Of Item Can\'t Be Less Than 1 Characters';
                }
                if(empty($desc)) {
                    $formErrors[] = 'Description Can\'t Be Empty';
                }
                if(empty($price)) {
                    $formErrors[] = 'Price Can\'t Be Empty';
                }
                if(empty($country)) {
                    $formErrors[] = 'Country Can\'t Be Empty';
                }
                if($status == 0) {
                    $formErrors[] = 'You Must Choose The Status';
                }
                if($member == 0) {
                    $formErrors[] = 'You Must Choose The Member';
                }
                if($cate == 0) {
                    $formErrors[] = 'You Must Choose The Category';
                }

                foreach($formErrors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>' . '<br/>';
                }

                // Check If There's No Error Proceed The Update Operation
                if(empty($formErrors)) {
                    // Update The Database With This Info

                    $stmt = $con->prepare("UPDATE
                                                items
                                            SET
                                                `Name` = ?,
                                                `Description` = ?,
                                                `Price` = ?,
                                                `Country_Made` = ?,
                                                `Status` = ?,
                                                `Cat_ID` = ?,
                                                `Member_ID` = ?
                                            WHERE
                                            `Item_ID` = ?
                                            ");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cate, $member, $id));

                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success' role='alert'>$name Is Updated</div>";
                    redirectHome($theMsg, 'previous', 5);
                }

            } else {
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Update Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

            echo '</div>';
        } elseif ($do == 'Delete') { // Delete Items Page
            echo '<h1 class="text-center">Delete Item</h1>';
            echo '<h1 class="container">';

            // Check If Get Request itemid Is Numeric & Get The Integer Value Of It
            $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Get The Name Of User To Use It With Echo
            $name = getOneWithID('Name', 'items', $itemid, 'Item_ID');

            // Select All Data Depend On This ID
            $check = checkItem('Item_ID', 'items', $itemid);

            // If There's Such ID Show The Form
            if ($check > 0) {
                // Delete The Member From Database
                $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = :zitem');
                //
                $stmt->bindParam(':zitem', $itemid);
                $stmt->execute();

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>" . $name . " Is Deleted From Items</div>";
                redirectHome($theMsg, 'back', 5);

            } else { // can't see this page
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Delete Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

            echo '</div>';
        } elseif ($do == 'Approve') { // Active Items To Appearing
            echo '<h1 class="text-center">Approve Item</h1>';
            echo '<h1 class="container">';

            // Check If Get Request itemid Is Numeric & Get The Integer Value Of It
            $itemid =  isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

            // Select All Data Depend On This ID
            $check = checkItem('Item_ID', 'items', $itemid);

            // If There's Such ID Show The Form
            if ($check > 0) {
                // Delete The Item From Database
                $stmt = $con->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
                //
                $stmt->execute(array($itemid));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>" . getOneWithID('Name', 'items', $itemid, 'Item_ID') . " Now Is Approved Item</div>";
                redirectHome($theMsg, 'back', 8);

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