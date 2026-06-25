<?php
require '../includes/auth-check.php';
require '../config/database.php';

$error   = '';

$movies = mysqli_query($conn, "SELECT id, title FROM movies WHERE status = 'showing'");

// fetch all shows with movie and theater info
$shows = mysqli_query($conn, "SELECT shows.*, movies.title as movie_title, theaters.name as theater_name 
                               FROM shows 
                               JOIN movies ON shows.movie_id = movies.id
                               JOIN theaters ON shows.theater_id = theaters.id");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id    = $_SESSION['user_id'];
    $show_id    = $_POST['show_id'];
    $seat_class = $_POST['seat_class'];
    $tickets    = (int)$_POST['tickets'];
    $kids       = (int)$_POST['kids'];

    // get show prices from database
    $show_stmt = mysqli_prepare($conn, "SELECT * FROM shows WHERE id = ?");
    mysqli_stmt_bind_param($show_stmt, 'i', $show_id);
    mysqli_stmt_execute($show_stmt);
    $show_result = mysqli_stmt_get_result($show_stmt);
    $show = mysqli_fetch_assoc($show_result);

    // calculate price based on seat class
    if($seat_class === 'gold')          $price = $show['gold_price'];
    elseif($seat_class === 'platinum')  $price = $show['platinum_price'];
    else                                $price = $show['box_price'];

    // kids get 50% discount
    $kids_discount = 0.5;
    $total = ($tickets * $price) + ($kids * $price * $kids_discount);

    // save booking
   $stmt = mysqli_prepare($conn, "INSERT INTO bookings (user_id, show_id, seat_class, tickets, kids, total, status) VALUES (?,?,?,?,?,?,'pending')");
   mysqli_stmt_bind_param($stmt, 'iisiid', $user_id, $show_id, $seat_class, $tickets, $kids, $total);

    if(!mysqli_stmt_execute($stmt)){
        $error = "Something went wrong. Please try again.";
    } else {
        $booking_id = mysqli_insert_id($conn);
        header('Location: payment.php?booking_id=' . $booking_id);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Book Tickets – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
    <link rel="stylesheet" href="../assets/css/booking.css"/>   
</head>
<body>

<?php require '../includes/header.php'; ?>

<div class="booking-wrap">

    <div class="booking-eyebrow">CinemAura</div>
    <h1 class="booking-title">Book Your <span>Tickets</span></h1>
    <p class="booking-sub">Select your movie, showtime and seat class below.</p>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="booking-card">
        <form method="post" action="">

            <div class="field">
                <label>Select Movie</label>
                <select name="movie_id">
                    <?php while($m = mysqli_fetch_assoc($movies)): ?>
                        <option value="<?= $m['id'] ?>"><?= $m['title'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="field">
                <label>Select Show</label>
                <select name="show_id">
                    <?php while($s = mysqli_fetch_assoc($shows)): ?>
                        <option value="<?= $s['id'] ?>">
                            <?= $s['movie_title'] ?> — <?= $s['theater_name'] ?> | <?= $s['show_date'] ?> at <?= date('h:i A', strtotime($s['show_time'])) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="field">
                <label>Seat Class</label>
                <select name="seat_class">
                    <option value="gold">🥇 Gold</option>
                    <option value="platinum">💎 Platinum</option>
                    <option value="box">📦 Box</option>
                </select>
            </div>

            <hr class="divider"/>

            <div class="field-row">
                <div class="field">
                    <label>Number of Tickets</label>
                    <input type="number" name="tickets" min="1" max="10" value="1"/>
                </div>
                <div class="field">
                    <label>Kids (Ages 3–12)</label>
                    <input type="number" name="kids" min="0" max="10" value="0"/>
                    <div class="kids-note">Kids get 50% discount</div>
                </div>
            </div>

            <hr class="divider"/>

            <button type="submit" class="btn-submit">Confirm Booking</button>

        </form>
    </div>
</div>

</body>
</html>