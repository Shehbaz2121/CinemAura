<?php
require '../includes/admin-check.php';
require '../config/database.php';

$users = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Users – CinemAura Admin</title>
</head>
<body>
<?php require '../includes/admin-header.php'; ?>

<div class="admin-wrap">

    <div class="page-header">
        <div class="page-eyebrow">Admin Panel</div>
        <h1 class="page-title">Registered <span>Users</span></h1>
        <p class="page-sub">View all users registered on CinemAura.</p>
    </div>

    <div class="admin-card">
        <h2 class="card-title">All Users</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['full_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><span class="badge <?= $user['role'] === 'admin' ? 'badge-gold' : 'badge-green' ?>"><?= $user['role'] ?></span></td>
                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>