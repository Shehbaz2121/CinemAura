<?php
require '../includes/admin-check.php';
require '../config/database.php';


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM shows WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $movie_id      = $_POST['movie_id'];
    $theater_id    = $_POST['theater_id'];
    $show_date     = $_POST['show_date'];
    $show_time     = $_POST['show_time'];
    $gold_price    = $_POST['gold_price'];
    $platinum_price = $_POST['platinum_price'];
    $box_price     = $_POST['box_price'];

    $stmt = mysqli_prepare($conn, "INSERT INTO shows (movie_id, theater_id, show_date, show_time, gold_price, platinum_price, box_price) VALUES (?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'iissddd', $movie_id, $theater_id, $show_date, $show_time, $gold_price, $platinum_price, $box_price);

    if(mysqli_stmt_execute($stmt)){
    $success = "Show added successfully!";
} else {
    $error = "Something went wrong. Please try again.";
} }

// 3. fetch all movies for dropdown
$movies = mysqli_query($conn, "SELECT id, title FROM movies");

// 4. fetch all theaters for dropdown
$theaters = mysqli_query($conn, "SELECT id, name FROM theaters");

// 5. fetch all shows
$shows = mysqli_query($conn, "SELECT shows.*, movies.title as movie_title, theaters.name as theater_name 
                               FROM shows 
                               JOIN movies ON shows.movie_id = movies.id 
                               JOIN theaters ON shows.theater_id = theaters.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if(isset($success)): ?>
    <p><?= $success ?></p>
        <?php endif; ?>

        <?php if(isset($error)): ?>
    <p><?= $error ?></p>
        <?php endif; ?>

       <form action="" method="post">
    <label>Movie</label>
    <select name="movie_id">
        <?php while($m = mysqli_fetch_assoc($movies)): ?>
            <option value="<?= $m['id'] ?>"><?= $m['title'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Theater</label>
    <select name="theater_id">
        <?php while($t = mysqli_fetch_assoc($theaters)): ?>
            <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Date</label>
    <input type="date" name="show_date">

    <label>Time</label>
    <input type="time" name="show_time">

    <label>Gold Price</label>
    <input type="number" name="gold_price">

    <label>Platinum Price</label>
    <input type="number" name="platinum_price">

    <label>Box Price</label>
    <input type="number" name="box_price">

    <button type="submit">Add Show</button>
</form>
            <h2>All Shows</h2>
<table border="1">
    <tr>
        <th>Movie</th>
        <th>Theater</th>
        <th>Date</th>
        <th>Time</th>
        <th>Gold</th>
        <th>Platinum</th>
        <th>Box</th>
        <th>Action</th>
    </tr>
    <?php while($show = mysqli_fetch_assoc($shows)): ?>
    <tr>
        <td><?= $show['movie_title'] ?></td>
        <td><?= $show['theater_name'] ?></td>
        <td><?= $show['show_date'] ?></td>
        <td><?= $show['show_time'] ?></td>
        <td><?= $show['gold_price'] ?></td>
        <td><?= $show['platinum_price'] ?></td>
        <td><?= $show['box_price'] ?></td>
        <td><a href="?delete=<?= $show['id'] ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
