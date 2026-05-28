<?php
require '../includes/admin-check.php';
require '../config/database.php';

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM movies");
$row = mysqli_fetch_assoc($result);
$total_movies = $row['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM theaters");
$row = mysqli_fetch_assoc($result);
$total_theaters = $row['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$row = mysqli_fetch_assoc($result);
$total_users = $row['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings");
$row = mysqli_fetch_assoc($result);
$total_bookings = $row['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard – CinemAura</title>
</head>
<body>

<?php require '../includes/admin-header.php'; ?>

<div class="admin-wrap">

  <div class="page-header">
    <div class="page-eyebrow">Admin Panel</div>
    <h1 class="page-title">Admin <span>Dashboard</span></h1>
    <p class="page-sub">Welcome back, <?= $_SESSION['user_name'] ?></p>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">🎬</div>
      <div class="stat-num"><?= $total_movies ?></div>
      <div class="stat-label">Movies</div>
      <a href="movies.php" class="stat-link">Manage →</a>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🏛️</div>
      <div class="stat-num"><?= $total_theaters ?></div>
      <div class="stat-label">Theaters</div>
      <a href="theaters.php" class="stat-link">Manage →</a>
    </div>
    <div class="stat-card">
      <div class="stat-icon">👥</div>
      <div class="stat-num"><?= $total_users ?></div>
      <div class="stat-label">Users</div>
      <a href="users.php" class="stat-link">View →</a>
    </div>
    <div class="stat-card">
      <div class="stat-icon">🎟️</div>
      <div class="stat-num"><?= $total_bookings ?></div>
      <div class="stat-label">Bookings</div>
      <a href="#" class="stat-link">View →</a>
    </div>
  </div>

  <div class="quick-actions">
    <div class="quick-title">Quick Actions</div>
    <div class="actions-grid">
      <a href="movies.php" class="action-btn">🎬 Add Movie</a>
      <a href="theaters.php" class="action-btn">🏛️ Add Theater</a>
      <a href="shows.php" class="action-btn">🕐 Add Show</a>
      <a href="users.php" class="action-btn">👥 View Users</a>
    </div>
  </div>

</div>
</body>
</html>