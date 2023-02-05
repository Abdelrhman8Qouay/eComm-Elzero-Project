<?php

    function lang($phrase) {
        static $lang = array(

            // Dashboard Page

            'ADMIN_PAGE' => 'Admin Home',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
        );
        return $lang[$phrase];
    }