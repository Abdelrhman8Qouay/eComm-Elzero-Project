<?php

ob_start(); // Output Buffering Start

session_start();
if(isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard | Home';

    include 'init.php';

    /* Start Dashboard Page */
    $latestUsers = getLatest('*', 'users', 'UserID', 5); // get the 5 latest users Array

    $latestItems = getLatest('*', 'items', 'Item_ID', 5); // get the 5 latest items Array

    ?>
    <div class="container home-stats text-center">
        <h1 class="">Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <span class="material-symbols-outlined">group</span>
                    Total Members
                    <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                <span class="material-symbols-outlined">person_add</span>
                    Pending Members
                    <span><a href="members.php?do=Manage&page=Pending">
                        <?php echo checkItem('RegStatus', 'users', 0) ?>
                    </a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                <span class="material-symbols-outlined">sell</span>
                    Total Items
                    <span><a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                <span class="material-symbols-outlined">forum</span>
                    Total Comments
                    <span>0</span>
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
                        <span class="material-symbols-outlined selecting">add</span>
                    </div>
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                        <?php
                            foreach ($latestUsers as $user) {
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
                <div class="card card_latest_users">
                    <div class="card-header">
                        <span class="material-symbols-outlined">sell</span> Latest Items
                        <span class="material-symbols-outlined selecting">add</span>
                    </div>
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                        <?php
                            foreach ($latestItems as $item) {
                                $stat = $item['Approve'] === 1 ? 'approved': '';
                                echo '<li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <a href="items.php?do=Edit&itemid='.$item['Item_ID'].'" class="fw-bold">' . $item['Name'] .'</a>
                                                '.$item['Price'] .'
                                            </div>
                                            <span class="badge bg-primary rounded-pill">'. $stat .'</span>
                                        </li>';
                            }
                        ?>
                        </ol>
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