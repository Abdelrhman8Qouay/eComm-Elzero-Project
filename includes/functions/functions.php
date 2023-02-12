<?php

    /*
    ** Get Categories Function v1.0
    ** Function To Get Categories From Database [ Users, Items, Comments ]
    */
    function getCat() {
        global $con;
        $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
        $getCat->execute();

        $cats = $getCat->fetchAll();

        return $cats;
    }

    /*
    ** Get Items Function v1.0
    ** Function To Get Items From Database [ Users, Items, Comments ]
    */
    function getItems($CatID) {
        global $con;
        $getItems = $con->prepare("SELECT * FROM items WHERE Cat_ID = ? ORDER BY Item_ID DESC");
        $getItems->execute(array($CatID));

        $items = $getItems->fetchAll();

        return $items;
    }















    // -------------------- From Admin Functions -------------------------

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
    ** Home Redirect Function v2.0
    ** Redirect Function [ This Function Accept Parameters ]
    ** $theMsg = Echo The The Message [ Error | Success | Warning | etc... ]
    ** $url = The Link You Want To Redirect To
    ** $seconds = Seconds Before Redirecting
    */
    function redirectHome($theMsg, $url = null, $seconds = 3) {

        if ($url === null) {
            $url = 'index.php';
        } else {
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
            } else {
                $url = 'index.php';
            }
        }
        echo '<div class="container my-3">';

        echo $theMsg;

        echo '
                <div class="alert alert-info" role="alert">
                    You Will Be Redirected To Another Page In ' . $seconds .
                ' seconds</div>
        ';

        echo '</div>';

        header("refresh:$seconds;url=$url");
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

    /*
    ** Count Number Of Items Function v1.0
    ** Function To Count Number Of Items Rows
    ** $items = The Item To Count
    ** $table = The Table To Choose From
    */
    function countItems($item, $table) {
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();

        // return the numbers for all items id database INT(Type)
        return $stmt2->fetchColumn();
    }

    /* SELF
    ** Get One Information With ID Function v1.0
    ** Function Get One Information In Database And Echo The Return [ Function Accept Parameters ]
    ** $fieldInfo = The Field To Fetch The Info From It [ Example: username, password, email ]
    ** $from  = The Table To Select From [ Example: user, item, categories ]
    ** $value = The Value Is The ID Of The Item To Get From It
    */
    function getOneWithID($fieldInfo, $from, $value = '', $fieldID = '') {
        global $con;

        if(empty($value) &&  empty($fieldID)) {
            $statement = $con->prepare("SELECT ($fieldInfo) FROM $from ");
            $statement->execute();
        } else {
            $statement = $con->prepare("SELECT ($fieldInfo) FROM $from WHERE $fieldID = ?");
            $statement->execute(array($value));
        }

        $getName = $statement->fetch();

        return $getName[$fieldInfo];
    }

    /*
    ** Get Latest Records Function v1.0
    ** Function To Get Latest Items From Database [ Users, Items, Comments ]
    ** $fieldInfo = Field To Select
    ** $table = The Table To Choose From
    ** $limit = Number Of Records To Get
    ** $order = The field from which the results are generated
    */
    function getLatest($fieldInfo, $table ,$order , $limit = 5) {
        global $con;
        $getStmt = $con->prepare("SELECT $fieldInfo FROM $table ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();

        $rows = $getStmt->fetchAll();

        return $rows;
    }

    /* SELF
    ** checkIfExistInfo Function v1.0
    ** Function To Check If This info Is Exist Or Not And Return A number (1: if Exist) [ Function Accept Params ]
    ** $select = What You Want To Select From The Table To Check On It
    ** $table = The Table Which You Will Check On It
    ** $sameField = The field whose values you want to compare with the current value from the same field
    ** $idField = The Field Of The Id To understand it That You Don't Mean The Origin $valueField Which Exist In Database (You Can Make It Null If No Id Field)
    ** $valueSame = The Value Of $sameField Variable
    ** $valueId = The Value Of $idField Variable (You Can Make It Null If No Value)
    */
    function checkIfExistInfo($select, $table, $sameField, $idField = null, $valueSame, $valueId = null) {
        global $con;

        if($idField == null && $valueId == null) {
            $statement = $con->prepare("SELECT $select FROM $table WHERE $sameField = ?");

            $statement->execute(array($valueSame));
        } else {
            $statement = $con->prepare("SELECT $select FROM $table WHERE $sameField = ? AND $idField != ?");

            $statement->execute(array($valueSame, $valueId));
        }

        $count = $statement->rowCount();

        return $count;

        // To Understand The statement >>>>>>
        // $statement = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
        // $statement->execute(array($username, $id));
    }