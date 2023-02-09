<?php

/*
    ===================================================
    == Manage Members Page
    == You Can Add | Edit | Delete Members From Here
    ===================================================
    */

    session_start();

    $pageTitle = 'Categories';

    if(isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard | Categories';

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Category Page

            $sort = 'ASC';
            $sort_array = array('ASC', 'DESC');
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll();
            ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container">
                <div class="mb-3">
                    <a href="categories.php?do=Add" class="btn btn-info"><span class="material-symbols-outlined">add</span> Add New Category</a>
                </div>
                <div class="card categories-card">
                    <div class="card-header">Manage Category
                        <div class="ordering float-end">
                            Ordering By:
                            <a class="<?php if($sort == 'ASC'){echo 'active';}?>" href="?sort=ASC">ASC</a> |
                            <a class="<?php if($sort == 'DESC'){echo 'active';}?>" href="?sort=DESC">DESC</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group face-cat">
                            <?php
                                foreach($cats as $cat) {
                                    echo '<li class="list-group-item d-flex justify-content-between align-items-start">';
                                        echo '<div class="ms-2 me-auto">';
                                            echo '<div class="fw-bold">'. $cat['Name'] .'</div>';
                                            echo '<div class="full-view">';
                                                if(empty($cat['Description'])) { echo '......'; } else { echo  $cat['Description']; }
                                                echo '<div class="fw-bold">';
                                                    if($cat['Visibility'] == 1) { echo '<span title="hidden category visibility" class="badge bg-danger rounded-pill"><span class="material-symbols-outlined">visibility</span>Hidden</span>'; }
                                                    if($cat['Allow_Comment'] == 1) { echo '<span title="Disabled category Allow Commenting" class="badge bg-warning rounded-pill"><span class="material-symbols-outlined">speaker_notes_off</span>Comment Disabled</span>'; }
                                                    if($cat['Allow_Ads'] == 1) { echo '<span title="Disabled category Allow Ads" class="badge bg-primary rounded-pill"><span class="material-symbols-outlined">disabled_by_default</span>Ads Disabled</span>'; }
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<a href="categories.php?do=Edit&catid='. $cat['ID'] .'" class="badge editing-btn">Edit</a>';
                                        echo '<a href="categories.php?do=Delete&catid='. $cat['ID'] .'" class="badge editing-btn confirma-message">Delete</a>';
                                    echo '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <?php

        } elseif ($do == 'Add') { // Add Category Page

            ?>
            <div class="container my-3">
                <h1 class="text-center">Add New Category</h1>

                <form class="row g-3 needs-validation" action="?do=Insert" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name Of The Category" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Describe The Category">
                    </div>
                    <div class="mb-3">
                        <label for="ordering" class="form-label">Ordering</label>
                        <input type="text" class="form-control" id="ordering" name="ordering" placeholder="Number To Arrange The Categories">
                    </div>
                    <div class="mb-3 row">
                        <label class="col-1 form-check-label">Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="visibility" id="vis-yes" value="0" class="form-check-input" checked>
                                <label for="vis-yes" class="form-check-label">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="visibility" id="vis-no" value="1" class="form-check-input">
                                <label for="vis-no" class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-auto form-check-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="commenting" id="comm-yes" value="0" class="form-check-input" checked>
                                <label for="comm-yes" class="form-check-label">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="commenting" id="comm-no" value="1" class="form-check-input">
                                <label for="comm-no" class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-auto form-check-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" name="ads" id="ads-yes" value="0" class="form-check-input" checked>
                                <label for="ads-yes" class="form-check-label">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="ads" id="ads-no" value="1" class="form-check-input">
                                <label for="ads-no" class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>

            <?php

        } elseif ($do == 'Insert') { // Insert Page

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo '<h1 class="text-center text-dark">Insert Category</h1>';
                echo '<div class="container my-3">';

                // get The Variables From The Form
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $order  = $_POST['ordering'];
                $visible  = $_POST['visibility'];
                $comment  = $_POST['commenting'];
                $ads  = $_POST['ads'];

                //Check If Category Exist In Database
                $check = checkItem("Name", "categories", $name);

                if($check == 1) {
                    $theMsg = '<div class="alert alert-danger" role="alert">Sorry This Category Is Already Exist</div>';
                    redirectHome($theMsg,'previous' , 5);
                } else {

                    // Insert Category Info In Database
                    $stmt = $con->prepare("INSERT INTO
                                                categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                            VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads) ");
                    $stmt->execute(array('zname' => $name, 'zdesc' => $desc, 'zorder' => $order, 'zvisible' => $visible, 'zcomment' => $comment, 'zads' => $ads));

                    // Echo Success Message
                    $theMsg = "<div class='alert alert-success' role='alert'>". $name ." Category Inserted Successfully</div>";
                    redirectHome($theMsg, null, 5);
                }



                echo '</div>';
            } else { // You Can't See this Page
                $theMsg = "<div class='alert alert-danger' role='alert'>Error Insert Page: Sorry You Can\'t Browse This Page Directly</div>";
                redirectHome($theMsg, null ,5);
            }

        } elseif ($do == 'Edit') { //Edit Page

            // Check If Get Request catid Is Numeric & Get The Integer Value Of It
            $catid =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

            // Execute Query
            $stmt->execute(array($catid));
            // Fetch The Data
            $cat = $stmt->fetch();
            // The Row Count If Exists with This ID
            $count = $stmt->rowCount();
            // If There's Such ID Show The Form
            if ($stmt->rowCount() > 0) {

                ?>

                <div class="container my-3">
                    <h1 class="text-center">Edit Category</h1>

                    <form class="row g-3 needs-validation" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>" />
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name Of The Category" required value="<?php echo $cat['Name'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" name="description" id="description" placeholder="Describe The Category" value="<?php echo $cat['Description'] ?>" >
                        </div>
                        <div class="mb-3">
                            <label for="ordering" class="form-label">Ordering</label>
                            <input type="text" class="form-control" id="ordering" name="ordering" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>" >
                        </div>
                        <div class="mb-3 row">
                            <label class="col-1 form-check-label">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="visibility" id="vis-yes" value="0" class="form-check-input" <?php if($cat['Visibility']== 0){ echo 'checked';}?> >
                                    <label for="vis-yes" class="form-check-label">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="visibility" id="vis-no" value="1" class="form-check-input" <?php if($cat['Visibility']== 1){ echo 'checked';}?>>
                                    <label for="vis-no" class="form-check-label">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-auto form-check-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="commenting" id="comm-yes" value="0" class="form-check-input"  <?php if($cat['Allow_Comment']== 0){ echo 'checked';}?>>
                                    <label for="comm-yes" class="form-check-label">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="commenting" id="comm-no" value="1" class="form-check-input" <?php if($cat['Allow_Comment']== 1){ echo 'checked';}?>>
                                    <label for="comm-no" class="form-check-label">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-auto form-check-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="ads" id="ads-yes" value="0" class="form-check-input" <?php if($cat['Allow_Ads']== 0){ echo 'checked';}?>>
                                    <label for="ads-yes" class="form-check-label">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="ads" id="ads-no" value="1" class="form-check-input" <?php if($cat['Allow_Ads']== 1){ echo 'checked';}?>>
                                    <label for="ads-no" class="form-check-label">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Edit Category" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>

                <?php
            } else {
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-danger">There\'s No Such ID</div>';
                redirectHome($theMsg);
                echo '</div>';

            }

        } elseif ($do == 'Update') { // Update Page

            echo '<h1 class="text-center text-dark">Update Category</h1>';
            echo '<div class="container my-3">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get The Variables From The Form
                $id  = $_POST['catid'];
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $order  = $_POST['ordering'];
                $visible  = $_POST['visibility'];
                $comment  = $_POST['commenting'];
                $ads  = $_POST['ads'];

                // Update The Database With This Info

                $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?,  Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
                $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>{$name} Category Is Updated Successfully</div>";
                redirectHome($theMsg, 'back', 5);
            }

        } elseif ($do == 'Delete') { // Delete Member

            echo '<h1 class="text-center">Delete Category</h1>';
            echo '<h1 class="container">';

            // Check If Get Request catid Is Numeric & Get The Integer Value Of It
            $catid =  isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Get The Name Of Category To Use It With Echo
            $name = getOneWithID('Name', 'categories', intval($catid), 'ID');

            // Select All Data Depend On This ID
            $check = checkItem('ID', 'categories', $catid);

            // If There's Such ID Show The Form
            if ($check > 0) {
                // Delete The Member From Database
                $stmt = $con->prepare('DELETE FROM categories WHERE ID = :zid');
                //
                $stmt->bindParam(':zid', $catid);
                $stmt->execute();

                // Echo Success Message
                $theMsg = "<div class='alert alert-success' role='alert'>" . $name . " Is Deleted From Categories</div>";
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