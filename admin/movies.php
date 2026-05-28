<?php
require '../includes/admin-check.php';
require '../config/database.php';
?>  
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
    }




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php require '../includes/admin-header.php'; ?>
    <form action="" method="post">
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