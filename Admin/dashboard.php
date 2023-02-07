<?php

ob_start(); // Output Buffering Start

session_start();
if(isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard | Home';

    include 'init.php';

    /* Start Dashboard Page */
    $theLatest = getLatest('*', 'users', 'UserID', 5); // get the 5 latest users

    ?>
    <div class="container home-stats text-center">
        <h1 class="">Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    Total Members
                    <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    Pending Members
                    <span><a href="members.php?do=Manage&page=Pending">
                        <?php echo checkItem('RegStatus', 'users', 0) ?>
                    </a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    Total Items
                    <span>1500</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    Total Comments
                    <span>3500</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container latest my-3">
        <div class="row">
            <div class="col-sm-6">
                <div class="card card_latest_users">
                    <div class="card-header">
                        <span class="material-symbols-outlined">inventory_2</span> Latest Registered Users
                    </div>
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                        <?php
                            foreach ($theLatest as $user) {
                                $stat = $user['RegStatus'] === 1 ? 'activate': '';
                                echo '<li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <a href="members.php?do=Edit&userid='.$user['UserID'].'" class="fw-bold">' . $user['Username'] .'</a>
                                                '.$user['Email'] .'
                                            </div>
                                            <span class="badge bg-primary rounded-pill">'. $stat .'</span>
                                        </li>';
                            }
                        ?>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <span class="material-symbols-outlined">sell</span> Latest Items
                    </div>
                    <div class="card-body">
                        test
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    /* End Dashboard Page */

    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}

ob_end_flush();