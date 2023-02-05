<?php

    function lang($phrase) {
        static $lang = array(
            'MESSAGE' => 'أهلا بك',
            'ADMIN' => 'Administrator'
        );
        return $lang[$phrase];
    }
