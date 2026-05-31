<?php
require '../includes/admin-check.php';
require '../config/database.php';

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM theaters WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $capacity = $_POST['capacity'];

    $stmt = mysqli_prepare($conn,"INSERT INTO theaters (name,location,capacity) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt,'ssi',$name,$location,$capacity);

     if(mysqli_stmt_execute($stmt)){
    $success = "Theater added successfully!";
    } else {
    $error = "Something went wrong. Please try again.";
    }
}

$theater_result = mysqli_query($conn, "SELECT * FROM theaters");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
 <label for="name">Name</label>
 <input type="text" name="name">
 <label for="location">Location</label>
 <input type="text" name="location">
 <label for="capacity">Capacity</label>
 <input type="number" name="capacity">
 <button type="submit">Submit</button>
</form>

    <h2>All Theaters</h2>
    <table border="1">
    <tr>
        <th>Name</th>
        <th>Location</th>
        <th>Capacity</th>
        <th>Action</th>
    </tr>
    <?php while($theater = mysqli_fetch_assoc($theater_result)): ?>
    <tr>
        <td><?= $theater['name'] ?></td>
        <td><?= $theater['location'] ?></td>
        <td><?= $theater['capacity'] ?></td>
        <td><a href="?delete=<?= $theater['id'] ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>