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
    $name     = trim($_POST['name']);
    $location = trim($_POST['location']);
    $capacity = $_POST['capacity'];

    $stmt = mysqli_prepare($conn, "INSERT INTO theaters (name,location,capacity) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $location, $capacity);

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
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Theaters – CinemAura Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/admin.css"/>
<body>
<?php require '../includes/admin-header.php'; ?>

<div class="admin-wrap">

    <div class="page-header">
        <div class="page-eyebrow">Admin Panel</div>
        <h1 class="page-title">Manage <span>Theaters</span></h1>
        <p class="page-sub">Add, view and delete theaters from the system.</p>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="admin-card">
        <h2 class="card-title">Add New Theater</h2>
        <form action="" method="post" class="admin-form">
            <div class="form-row">
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="e.g. CinemAura Karachi"/>
                </div>
                <div class="field">
                    <label>Location</label>
                    <input type="text" name="location" placeholder="e.g. Karachi, Pakistan"/>
                </div>
                <div class="field">
                    <label>Capacity</label>
                    <input type="number" name="capacity" placeholder="e.g. 200"/>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-gold">Add Theater</button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <h2 class="card-title">All Theaters</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($theater = mysqli_fetch_assoc($theater_result)): ?>
                <tr>
                    <td><?= $theater['name'] ?></td>
                    <td><?= $theater['location'] ?></td>
                    <td><?= $theater['capacity'] ?></td>
                    <td><a href="?delete=<?= $theater['id'] ?>" class="btn-delete" onclick="return confirm('Delete this theater?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>