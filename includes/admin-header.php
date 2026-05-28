<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="../assets/css/admin.css"/>

<nav>
  <a href="../admin/index.php" class="logo">Cinem<span>Aura</span> <small>Admin</small></a>

  <ul class="nav-links">
    <li><a href="../admin/index.php">Dashboard</a></li>
    <li><a href="../admin/movies.php">Movies</a></li>
    <li><a href="../admin/shows.php">Shows</a></li>
    <li><a href="../admin/theaters.php">Theaters</a></li>
    <li><a href="../admin/users.php">Users</a></li>
  </ul>

  <div class="nav-actions">
    <a href="../pages/home.php" class="btn btn-outline">← Back to Site</a>
    <span>Welcome, <?= $_SESSION['user_name'] ?></span>
    <a href="../auth/logout.php" class="btn btn-gold">Logout</a>
  </div>
</nav>