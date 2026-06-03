
<link rel="stylesheet" href="../assets/css/homenavfoot.css">
<nav>
  <a href="../index.php" class="logo">Cinem<span>Aura</span></a>
  <ul class="nav-links">
    <li><a href="movies.php">Movies</a></li>
    <li><a href="#">Theaters</a></li>
    <li><a href="showtimes.php">Showtimes</a></li>
    <li><a href="#">Reviews</a></li>
  </ul>
  <div class="nav-actions">
    <?php if(isset($_SESSION['user_id'])): ?>
          <a href="#">Welcome, <?= $_SESSION['user_name'] ?></a>
          <a href="../auth/logout.php" class="btn btn-gold">Logout</a>
        <?php if($_SESSION['user_role'] === 'admin'): ?>
          <a href="../admin/index.php" class="btn btn-gold">Dashboard</a>
          <?php endif; ?>
          <?php else: ?>
        <a href="../auth/login.php" class="btn btn-outline">Sign In</a>
        <a href="../auth/register.php" class="btn btn-gold">Register</a>

    <?php endif; ?>
</div>
</nav>