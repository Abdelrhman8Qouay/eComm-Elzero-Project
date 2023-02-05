<?php

    /*
    You Can Do All Of these Pages In One Page Only
        Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
    */

    // Controlling Your Output Of Page With (Url => Get) Information
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // If The Page Is Main Page

    if ($do == 'Manage') {
        echo 'Welcome You Are In Manage Category Page';
        echo '';
    } elseif ($do == 'Add') {
        echo 'welcome You Are In Add Category Page';
    } else {
        echo 'Error There\'s No Page With This Name';
    }
