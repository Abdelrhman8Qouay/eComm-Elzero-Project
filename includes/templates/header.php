

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="layout/css/frontend.css">
    <title><?php getTitle() ?></title>
</head>
<body>
<div class="upper-bar">
    <div class="container">
      <?php
        if(isset($_SESSION['user'])) {
            echo 'Welcome ' . $_SESSION['user'];
            echo ' <a href="profile.php">My Profile</a>';
            echo ' <a href="logout.php">Logout</a>';
            if (checkUserStatus($sessionUser) == 1) {
              // User Is Not Active

            }
        } else {
      ?>
      <a href="login.php">
        <span class="text-end">Login/Signup</span>
      </a>
      <?php } ?>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark ">
  <div class="container">
    <a class="navbar-brand" href="index.php">Homepage</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-right">
        <?php
        foreach (getCat() as $cat) {
            echo '<li class="nav-item"><a class="nav-link" href="categories.php?pageid='. $cat['ID'] .'&pagename='. str_replace(' ','-',$cat['Name']) .'">' . $cat['Name'] . '</a></li>';
        }
        ?>
        </ul>
    </div>
  </div>
</nav>

<style>

/* Start upper-bar Part */
.upper-bar {
    padding: 10px;
    background-color: var(--light-dark);
    color: var(--background-color);
}
.upper-bar a {
    text-decoration: none;
    font-weight: bold;
    color: var(--text-white);
    transition: 0.3s;
}
.upper-bar a:hover {
    color: var(--text-dark);
}

/* End upper-bar Part */
/* --------------------------------*/
/* Start navbar Edits */
.navbar {
    background-color: var(--text-dark);
}

.navbar .container .navbar-collapse .navbar-nav .nav-item .active {
    color: var(--text-white);
}

.navbar .container #userTarget {
    color: var(--deg-color-1);
}


/* End navbar Edits */
</style>


