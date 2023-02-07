<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="categories.php">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Items</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="members.php">Members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Statistics</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Logs</a>
        </li>
      </ul>
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userTarget" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $_SESSION['Username'] ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="userTarget">
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>