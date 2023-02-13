<?php
    session_start();
    $pageTitle = 'Profile';

    include 'init.php';

    if(isset($_SESSION['user'])) {

        $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();
?>
    <div class="container">
        <h1 class="text-center">My Profile</h1>

        <div class="card card_inf my-3">
            <div class="card-header">My Information</div>
            <div class="card-body">Name: <?php echo $info['Username'] ?><br>
                Email: <?php echo $info['Email'] ?><br>
                Registered Date: <?php echo $info['Date'] ?>
            </div>
        </div>

        <div class="card card_inf my-3">
            <div class="card-header">My Ads</div>
            <div class="card-body">
                <div class="row">
                <?php
                foreach(getItems('Member_ID', $info['UserID']) as $item){
                    echo '<div class="col-sm-6 col-md-3 mt-3">';
                        echo '<div class="card item-box">';
                            echo '<span class="price-tag">'. $item['Price'] .'</span>';
                            echo '<img src="NonUser.png" alt="non image user" class="img-thumbnail" >';
                            echo '<div class="card-body">
                                    <h5 class="card-title">'. $item['Name'] .'</h5>
                                    <p class="card-text">'. $item['Description'] .'</p>
                                </div>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
                </div>
            </div>
        </div>

        <div class="card card_inf my-3">
            <div class="card-header">My Comments</div>
            <div class="card-body d-flex flex-column">
                <?php
                // Select All Users Except Admin
                $stmt = $con->prepare("SELECT * FROM comments WHERE user_id = ?");
                $stmt->execute(array($info['UserID']));
                // Assign To Variable
                $comments = $stmt->fetchAll();

                if(!empty($comments)){
                    foreach($comments as $comment){
                        echo '<div class="toast showing comment_con w-100 my-3" role="alert" aria-atomic="true">
                                <div class="toast-header">
                                    <strong class="me-auto">Item >>'. getOneWithID('Name', 'items', 'Item_ID', $comment['item_id']) .'</strong>
                                    <small class="text-muted">'. $comment['comment_date'] .'</small>
                                </div>
                                <div class="toast-body">
                                    '. $comment['comment'] .'
                                </div>
                            </div>';
                    }
                } else {
                    echo 'There\'s No Comments To Show';
                }
                ?>
            </div>
        </div>
    </div>

    <style>
        .container .card_inf .card-header {
            background-color: var(--light-dark);
            color: var(--text-white);
        }
        .container .card_inf .comment_con {
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 400;

        }
    </style>
<?php
    } else {
        header('Location: login.php');
        exit();
    }


    include $tpl . 'footer.php';
?>