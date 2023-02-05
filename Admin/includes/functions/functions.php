<?php

    /*
        Dynamic Function For Page Title v1.0
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle Amd Echo Defualt Title For Other Pages
    */
    function getTitle() {
        global $pageTitle;
        if (isset($pageTitle)) {
            echo $pageTitle;
        } else {
            echo 'Default';
        }
    }

    /*
    ** Home Redirect Function v1.0
    ** Redirect Function [ This Function Accept Parameters ]
    ** $errorMsg = Echo The Error Message
    ** $seconds = Seconds Before Redirecting
    */
    function redirectHome($errorMsg, $seconds = 3) {
        echo '<div class="container my-3">
                        <div class="alert alert-danger" role="alert">
                            ' . $errorMsg .
                        '</div>
                    </div>
        ';
        echo '<div class="container my-3">
                <div class="alert alert-info" role="alert">
                    You Will Be Redirected To Home Page' . $seconds .
                '</div>
            </div>
        ';

        header("refresh:$seconds;url=index.php");
        exit();
    }

    /*
    ** Check Items Function v1.0
    ** Function Check Item In Database [ Function Accept Parameters ]
    ** $select = The Item To Select [ Example: user, item, category ]
    ** $from  = The Table To Select From [ Example: user, item, categories ]
    ** $value = The Value Of Select [ Example: osama, box, electronics ]
    */
    function checkItem($select, $from, $value) {
        global $con;

        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;
    }