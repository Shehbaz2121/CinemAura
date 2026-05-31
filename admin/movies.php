<?php
require '../includes/admin-check.php';
require '../config/database.php';

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM movies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title        = trim($_POST['title']);
    $genre        = trim($_POST['genre']);
    $description  = trim($_POST['description']);
    $duration     = $_POST['duration'];
    $rating       = $_POST['rating'];
    $year         = $_POST['year'];
    $trailer      = trim($_POST['trailer']);
    $status       = $_POST['status'];

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

$movies_result = mysqli_query($conn, "SELECT * FROM movies");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Movies – CinemAura Admin</title>
</head>
<body>
<?php require '../includes/admin-header.php'; ?>

<div class="admin-wrap">

    <div class="page-header">
        <div class="page-eyebrow">Admin Panel</div>
        <h1 class="page-title">Manage <span>Movies</span></h1>
        <p class="page-sub">Add, view and delete movies from the system.</p>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- ADD MOVIE FORM -->
    <div class="admin-card">
        <h2 class="card-title">Add New Movie</h2>
        <form action="" method="post" enctype="multipart/form-data" class="admin-form">

            <div class="form-row">
                <div class="field">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="e.g. Titanic"/>
                </div>
                <div class="field">
                    <label>Genre</label>
                    <input type="text" name="genre" placeholder="e.g. Drama"/>
                </div>
            </div>

            <div class="field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Movie description..."></textarea>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>Duration (mins)</label>
                    <input type="number" name="duration" placeholder="e.g. 120"/>
                </div>
                <div class="field">
                    <label>Rating</label>
                    <input type="number" name="rating" step="0.1" min="0" max="10" placeholder="e.g. 8.2"/>
                </div>
                <div class="field">
                    <label>Year</label>
                    <input type="number" name="year" placeholder="e.g. 2024"/>
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label>Poster Image</label>
                    <input type="file" name="poster" accept="image/*"/>
                </div>
                <div class="field">
                    <label>Trailer URL</label>
                    <input type="url" name="trailer" placeholder="https://youtube.com/..."/>
                </div>
            </div>

            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="showing">Now Showing</option>
                    <option value="upcoming">Upcoming</option>
                </select>
            </div>

            <button type="submit" class="btn btn-gold">Add Movie</button>
        </form>
    </div>

    <!-- MOVIES TABLE -->
    <div class="admin-card">
        <h2 class="card-title">All Movies</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($movie = mysqli_fetch_assoc($movies_result)): ?>
                <tr>
                    <td><?= $movie['title'] ?></td>
                    <td><?= $movie['genre'] ?></td>
                    <td><?= $movie['year'] ?></td>
                    <td>⭐ <?= $movie['rating'] ?></td>
                    <td><span class="badge <?= $movie['status'] === 'showing' ? 'badge-green' : 'badge-gold' ?>"><?= $movie['status'] ?></span></td>
                    <td><a href="?delete=<?= $movie['id'] ?>" class="btn-delete" onclick="return confirm('Delete this movie?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>