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
    $movie_id       = $_POST['movie_id'];
    $theater_id     = $_POST['theater_id'];
    $show_date      = $_POST['show_date'];
    $show_time      = $_POST['show_time'];
    $gold_price     = $_POST['gold_price'];
    $platinum_price = $_POST['platinum_price'];
    $box_price      = $_POST['box_price'];

    $stmt = mysqli_prepare($conn, "INSERT INTO shows (movie_id, theater_id, show_date, show_time, gold_price, platinum_price, box_price) VALUES (?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'iissddd', $movie_id, $theater_id, $show_date, $show_time, $gold_price, $platinum_price, $box_price);

    if(mysqli_stmt_execute($stmt)){
        $success = "Show added successfully!";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}

$movies   = mysqli_query($conn, "SELECT id, title FROM movies");
$theaters = mysqli_query($conn, "SELECT id, name FROM theaters");
$shows    = mysqli_query($conn, "SELECT shows.*, movies.title as movie_title, theaters.name as theater_name 
                                  FROM shows 
                                  JOIN movies ON shows.movie_id = movies.id 
                                  JOIN theaters ON shows.theater_id = theaters.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Shows – CinemAura Admin</title>
</head>
<body>
<?php require '../includes/admin-header.php'; ?>

<div class="admin-wrap">

    <div class="page-header">
        <div class="page-eyebrow">Admin Panel</div>
        <h1 class="page-title">Manage <span>Shows</span></h1>
        <p class="page-sub">Schedule movie showtimes across theaters.</p>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <h2 class="card-title">Add New Show</h2>
        <form action="" method="post" class="admin-form">
            <div class="form-row">
                <div class="field">
                    <label>Movie</label>
                    <select name="movie_id">
                        <?php while($m = mysqli_fetch_assoc($movies)): ?>
                            <option value="<?= $m['id'] ?>"><?= $m['title'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Theater</label>
                    <select name="theater_id">
                        <?php while($t = mysqli_fetch_assoc($theaters)): ?>
                            <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Date</label>
                    <input type="date" name="show_date"/>
                </div>
                <div class="field">
                    <label>Time</label>
                    <input type="time" name="show_time"/>
                </div>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Gold Price (PKR)</label>
                    <input type="number" name="gold_price" placeholder="e.g. 800"/>
                </div>
                <div class="field">
                    <label>Platinum Price (PKR)</label>
                    <input type="number" name="platinum_price" placeholder="e.g. 1400"/>
                </div>
                <div class="field">
                    <label>Box Price (PKR)</label>
                    <input type="number" name="box_price" placeholder="e.g. 2500"/>
                </div>
            </div>
            <button type="submit" class="btn btn-gold">Add Show</button>
        </form>
    </div>

    <div class="admin-card">
        <h2 class="card-title">All Shows</h2>
        <table class="admin-table">
            <thead>
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
            </thead>
            <tbody>
                <?php while($show = mysqli_fetch_assoc($shows)): ?>
                <tr>
                    <td><?= $show['movie_title'] ?></td>
                    <td><?= $show['theater_name'] ?></td>
                    <td><?= $show['show_date'] ?></td>
                    <td><?= $show['show_time'] ?></td>
                    <td>PKR <?= $show['gold_price'] ?></td>
                    <td>PKR <?= $show['platinum_price'] ?></td>
                    <td>PKR <?= $show['box_price'] ?></td>
                    <td><a href="?delete=<?= $show['id'] ?>" class="btn-delete" onclick="return confirm('Delete this show?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>