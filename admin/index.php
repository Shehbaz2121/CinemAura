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
  <title>Admin Dashboard – CinemAura</title>
</head>
<body>

  <h1>Admin Dashboard</h1>
  <p>Welcome, <?= $_SESSION['user_name'] ?></p>

  
  <div>
    <div>
      <h3>Movies</h3>
      <p><?= $total_movies ?></p>
    </div>

    <div>
      <h3>Theaters</h3>
      <p><?= $total_theaters ?></p>
    </div>

    <div>
      <h3>Users</h3>
      <p><?= $total_users ?></p>
    </div>

    <div>
      <h3>Bookings</h3>
      <p><?= $total_bookings ?></p>
    </div>

  
</div>

</body>
</html>