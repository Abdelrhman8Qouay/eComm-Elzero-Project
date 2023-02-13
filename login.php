<?php
    ob_start();
    session_start();
    $pageTitle = 'Login';

    // Note: You Can't given the same name for session same like of admin in backend (That Will Make The Normal User Access on Backend and admin part)
    // If This Username Exists In Session Redirect To The Page
    if(isset($_SESSION['user'])) {
        header('Location: index.php');
    }

    include 'init.php';

    // Check If User Coming From HTTP POST Request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        // to put for each request the errors with where come from
        $tagPlace = '';

        // Depend on submit button name if login or signup
        if(isset($_POST['login'])) {
            $errorMsg = '';
            $tagPlace = 'Login';

            $user = $_POST['username'];
            $pass = $_POST['password'];
            // To Protect The Password
            $hashedPass = sha1($pass);

            // Check If The User Exist In Database And also this man Admin
            $stmt = $con->prepare("SELECT
                                        Username, Password
                                    FROM
                                        users
                                    WHERE
                                        Username = ?
                                    AND
                                        Password = ?");
            $stmt->execute(array($user, $hashedPass));
            // Get The Count of Rows Of These Columns
            $count = $stmt->rowCount();

            // If Count > 0 This Mean The Database Contain Record About This Username
            if($count > 0){
                $_SESSION['user'] = $user; // Register Session Name

                header('Location: index.php'); // Redirect To Dashboard Page
                exit();
            }
        } else {
            $errorMsg = '';
            $tagPlace = 'SignUp';

            // Validate The Inputs From User
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email'])) {
                $filteredUser = filter_string_polyfill($_POST['username']);

                $pass1 = sha1($_POST['password']);
                $pass2 = sha1($_POST['password2']);

                $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                if(strlen($filteredUser) < 4) {
                    $errorMsg = 'Username Must Be Larger Than 4 Characters';
                }elseif (strlen($filteredUser) > 20) {
                    $errorMsg = 'Username Must Be Less Than 20 Characters';
                }elseif (empty($filteredUser)) {
                    $errorMsg = 'Username Must Be Not Empty :(';
                }elseif($pass1 !== $pass2) {
                    $errorMsg = 'Sorry RePassword Is Not Match';
                }elseif(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $errorMsg = 'This Email Is Not Valid :(';
                } else {
                    echo "$filteredUser $pass1";
                }
            }
        }
    }
?>

<div class="container login_cont">

    <div class="card">
        <div class="card-header">
            <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
        </div>
        <div class="card-body">
            <!-- Start Login Form  -->
            <form class="form-floating form_loginSign form_login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" name="username" id="username" autocomplete="off" required />
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" required />
                    <label for="password">Password</label>
                </div>
                <div class="col-12">
                    <button class="btn btn-info" name="login" type="submit">Login</button>
                </div>
            </form>
            <!-- End Login Form  -->
            <!-- Start Signup Form  -->
            <form class="form-floating form_loginSign form_signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" name="username" id="username" autocomplete="off" />
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" />
                    <label for="password">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" name="password2" id="password2" autocomplete="new-password" />
                    <label for="password2">Repassword</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" name="email" id="email" />
                    <label for="email">Email</label>
                </div>
                <div class="col-12">
                    <button class="btn" name="signup" type="submit">Signup</button>
                </div>
            </form>
            <!-- Start Signup Form  -->

            <div class="errors text-center">
                <?php
                    if(!empty($errorMsg)) {
                        echo '<div class="alert alert-danger" role="alert">'. $tagPlace. ' Error: ' .$errorMsg.'</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
    .login_cont h1 span[data-class="login"].selected {
    color: var(--deg-color-3);
    }
    .login_cont .form_login button {
        background-color: var(--deg-color-3);
    }
    .login_cont h1 span[data-class="signup"].selected {
        color: var(--deg-color-5);
    }
    .login_cont .form_signup button {
        background-color: var(--deg-color-5);
    }
    .login_cont h1 span {
        cursor: pointer;
    }

    .login_cont .form_loginSign {
        max-width: 500px;
        margin: auto;
    }

    .login_cont .form_loginSign button {
        width: 100%;
        margin: 20px 0;
        border: none;
    }
    .login_cont .form_loginSign .form-floating {
        margin-bottom: 20px;
    }

    .login_cont .card {
        margin: 5rem auto;
        max-width: 700px;
        background: var(--light-dark);
    }

    .login_cont .form_signup {
        display: none;
    }

    /* Errors Message Part */
    .errors {
        color: red;
    }
</style>

<?php
    include $tpl . 'footer.php';

    ob_end_flush();
?>