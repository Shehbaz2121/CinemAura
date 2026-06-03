
<?php
require '../includes/auth-check.php';
require '../config/database.php';

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT bookings.*, shows.show_date, shows.show_time, 
                                movies.title as movie_title, theaters.name as theater_name
                                FROM bookings
                                JOIN shows ON bookings.show_id = shows.id
                                JOIN movies ON shows.movie_id = movies.id
                                JOIN theaters ON shows.theater_id = theaters.id
                                WHERE bookings.user_id = ?
                                ORDER BY bookings.booked_at DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Booking Confirmed – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
    <link rel="stylesheet" href="../assets/css/confirmation.css">
</head>
<body>

<?php require '../includes/header.php'; ?>

<div class="confirm-wrap">
    <div class="confirm-icon">🎟️</div>
    <div class="confirm-eyebrow">Booking Confirmed</div>
    <h1 class="confirm-title">Enjoy The <span>Show!</span></h1>
    <p class="confirm-sub">Your booking has been confirmed. See you at the cinema!</p>

    <?php if($booking): ?>
    <div class="ticket">
        <div class="ticket-header">
            <div class="ticket-movie"><?= $booking['movie_title'] ?></div>
            <div class="ticket-status">✓ Confirmed</div>
        </div>

        <div class="ticket-body">
            <div class="ticket-field">
                <label>Theater</label>
                <span><?= $booking['theater_name'] ?></span>
            </div>
            <div class="ticket-field">
                <label>Date</label>
                <span><?= date('M d, Y', strtotime($booking['show_date'])) ?></span>
            </div>
            <div class="ticket-field">
                <label>Time</label>
                <span><?= date('h:i A', strtotime($booking['show_time'])) ?></span>
            </div>
            <div class="ticket-field">
                <label>Seat Class</label>
                <span><?= ucfirst($booking['seat_class']) ?></span>
            </div>
            <div class="ticket-field">
                <label>Tickets</label>
                <span><?= $booking['tickets'] ?> Adult<?= $booking['tickets'] > 1 ? 's' : '' ?></span>
            </div>
            <div class="ticket-field">
                <label>Kids</label>
                <span><?= $booking['kids'] ?> Kid<?= $booking['kids'] != 1 ? 's' : '' ?></span>
            </div>
            <div class="ticket-field">
                <label>Booked By</label>
                <span><?= $_SESSION['user_name'] ?></span>
            </div>
            <div class="ticket-field">
                <label>Booking ID</label>
                <span>#<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?></span>
            </div>
        </div>

        <hr class="ticket-divider"/>

        <div class="ticket-total">
            <label>Total Paid</label>
            <span>PKR <?= number_format($booking['total'], 2) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="confirm-actions">
        <a href="booking.php" class="btn btn-outline">Book Another</a>
        <a href="home.php" class="btn btn-gold">Back to Home</a>
    </div>
</div>

</body>
</html>