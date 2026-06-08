<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="../assets/css/homenavfoot.css">
<nav>
  <a href="../index.php" class="logo">Cinem<span>Aura</span></a>
  <ul class="nav-links">
    <li><a href="movies.php">Movies</a></li>
    <li><a href="showtimes.php">Showtimes</a></li>
    <li><a href="booking.php">Book Tickets</a></li>
  </ul>
  <div class="nav-actions">
    <?php if (isset($_SESSION['user_id'])): ?>
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="../admin/index.php" class="btn btn-outline">Dashboard</a>
      <?php endif; ?>
      <a href="../auth/logout.php" class="btn btn-gold">Logout</a>
    <?php else: ?>
      <a href="../auth/login.php" class="btn btn-outline">Sign In</a>
      <a href="../auth/register.php" class="btn btn-gold">Register</a>
    <?php endif; ?>
  </div>
  
  </button>
</nav>