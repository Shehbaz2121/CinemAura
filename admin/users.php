<?php
require '../includes/admin-check.php';
require '../config/database.php';

$users = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users – CinemAura Admin</title>
</head>
<body>
    <?php require '../includes/admin-header.php'; ?>

    <h1>Registered Users</h1>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registered</th>
        </tr>
        <?php while($user = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['full_name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
            <td><?= $user['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>