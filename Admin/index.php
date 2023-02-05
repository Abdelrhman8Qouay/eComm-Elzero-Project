<?php
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';

    // If This Username Exists In Session Redirect To The Page
    if(isset($_SESSION['Username'])) {
        header('Location: dashboard.php'); // Redirect To Dashboard Page
    }

    include 'init.php';

    // Check If User Coming From HTTP POST Request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        // To Protect The Password
        $hashedPass = sha1($password);

        // Check If The User Exist In Database And also this man Admin
        $stmt = $con->prepare("SELECT
                                    UserID, Username, Password
                                FROM
                                    users
                                WHERE
                                    Username = ?
                                AND
                                    Password = ?
                                AND
                                    GroupID = 1
                                LIMIT
                                    1");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        // Get The Count of Rows Of These Columns
        $count = $stmt->rowCount();

        // If Count > 0 This Mean The Database Contain Record About This Username
        if($count > 0){
            $_SESSION['Username'] = $username; // Register Session Name
            $_SESSION['ID'] = $row['UserID']; // Register Session Id
            header('Location: dashboard.php'); // Redirect To Dashboard Page
            exit();
        }
    }
?>


    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="login">
        <h2 class="mb-3 text-secondary text-center">Admin Login</h2>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user" name="user" placeholder="UserName" autocomplete="off">
            <label for="user">Email address</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Password" autocomplete="new-password">
            <label for="pass">Password</label>
        </div>
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>
<?php
    include $tpl . 'footer.php';
?>