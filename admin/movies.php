<?php
require '../includes/admin-check.php';
require '../config/database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title       = trim($_POST['title']);
    $genre       = trim($_POST['genre']);
    $description = trim($_POST['description']);
    $duration    = $_POST['duration'];
    $rating      = $_POST['rating'];
    $year        = $_POST['year'];
    $trailer     = trim($_POST['trailer']);
    $status      = $_POST['status'];

    $poster = $_FILES['poster']['name'];
    $upload_dir = '../assets/images/';
    move_uploaded_file($_FILES['poster']['tmp_name'], $upload_dir . $poster);

    $stmt = mysqli_prepare($conn, "INSERT INTO movies (title, genre, description, duration, rating, year, trailer_url, status, poster) VALUES (?,?,?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sssidssss', $title, $genre, $description, $duration, $rating, $year, $trailer, $status, $poster);

    if(mysqli_stmt_execute($stmt)){
    $success = "Movie added successfully!";
    } else {
    $error = "Something went wrong. Please try again.";
    }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- <?php require '../includes/admin-header.php'; ?> -->
    <?php if(isset($success)): ?>
    <p><?= $success ?></p>
        <?php endif; ?>

        <?php if(isset($error)): ?>
    <p><?= $error ?></p>
        <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title">
        <label for="genre">Genre</label>
        <input type="text" name="genre">
        <label for="desc">Description:</label>
        <textarea id="desc" name="description" rows="4" cols="50" placeholder="Enter your description here..."></textarea>
        <label for="duration">Duration</label>
        <input type="number" name="duration" placeholder="e.g. 120">
        <label for="rating">Rating</label>
        <input type="number" name="rating">
        <label for="year">Year</label>
        <input type="number" name="year" placeholder="e.g. 2024">
        <label for="poster">Poster</label>
        <input type="file" name="poster" accept="image/*">
        <label for="trailer">Trailer</label>
        <input type="url" name="trailer">
        <label for="status">Status</label>
        <select name="status">
            <option value="showing">Showing</option>
            <option value="upcoming">Upcoming</option>
        </select>
        <button type="submit">Add Movie</button>
    </form>
</body>
</html>