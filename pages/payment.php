<?php
require '../includes/auth-check.php';
require '../config/database.php';

$error = '';

// get booking details from URL
$booking_id = $_GET['booking_id'] ?? 0;

// fetch booking
$stmt = mysqli_prepare($conn, "SELECT bookings.*, shows.show_date, shows.show_time,
                                movies.title as movie_title, theaters.name as theater_name
                                FROM bookings
                                JOIN shows ON bookings.show_id = shows.id
                                JOIN movies ON shows.movie_id = movies.id
                                JOIN theaters ON shows.theater_id = theaters.id
                                WHERE bookings.id = ? AND bookings.user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $booking_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if(!$booking){
    header('Location: booking.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // simulate payment — always succeeds
    header('Location: confirmation.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
   
</head>
<body>

<?php require '../includes/header.php'; ?>

<div class="page-wrap">

    <!-- LEFT — PAYMENT FORM -->
    <div>
        <div class="page-eyebrow">Checkout</div>
        <h1 class="page-title">Secure <span>Payment</span></h1>
        <p class="page-sub">Enter your card details to complete the booking.</p>
        <div class="card-number-preview" id="cardNum">•••• •••• •••• ••••</div>
        <div class="payment-card">

            <!-- card preview -->
            <div class="card-preview">
                <div class="card-chip"></div>
                <div class="card-number-preview" id="cardNum">•••• •••• •••• ••••</div>
                <div class="card-bottom">
                    <div>
                        <div style="font-size:10px;color:var(--text-muted);letter-spacing:1px;margin-bottom:2px;">Card Holder</div>
                        <div class="card-holder-preview" id="cardName">Your Name</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:var(--text-muted);letter-spacing:1px;margin-bottom:2px;">Expires</div>
                        <div class="card-expiry-preview" id="cardExp">MM/YY</div>
                    </div>
                    <div class="card-logo">VISA</div>
                </div>
            </div>

            <form method="post" action="">

                <div class="field">
                    <label>Card Holder Name</label>
                    <input type="text" name="card_name" placeholder="e.g. Ahmed Khan"
                           oninput="document.getElementById('cardName').textContent = this.value || 'Your Name'"
                           required/>
                </div>

                <div class="field">
                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                           maxlength="19"
                           oninput="formatCard(this)"
                           required/>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" name="expiry" placeholder="MM/YY"
                               maxlength="5"
                               oninput="formatExpiry(this)"
                               required/>
                    </div>
                    <div class="field">
                        <label>CVV</label>
                        <input type="password" name="cvv" placeholder="•••" maxlength="3" required/>
                    </div>
                </div>

                <hr class="divider"/>

                <div class="secure-note">
                    🔒 Your payment is 100% secure and encrypted
                </div>

                <button type="submit" class="btn-pay">
                    Pay PKR <?= number_format($booking['total'], 2) ?>
                </button>

            </form>
        </div>
    </div>

    <!-- RIGHT — ORDER SUMMARY -->
    <div class="summary-card">
        <div class="summary-title">Order Summary</div>
        <div class="summary-movie"><?= $booking['movie_title'] ?></div>

        <div class="summary-row">
            <span class="label">Theater</span>
            <span class="value"><?= $booking['theater_name'] ?></span>
        </div>
        <div class="summary-row">
            <span class="label">Date</span>
            <span class="value"><?= date('M d, Y', strtotime($booking['show_date'])) ?></span>
        </div>
        <div class="summary-row">
            <span class="label">Time</span>
            <span class="value"><?= date('h:i A', strtotime($booking['show_time'])) ?></span>
        </div>
        <div class="summary-row">
            <span class="label">Seat Class</span>
            <span class="value"><?= ucfirst($booking['seat_class']) ?></span>
        </div>
        <div class="summary-row">
            <span class="label">Tickets</span>
            <span class="value"><?= $booking['tickets'] ?> Adult<?= $booking['tickets'] > 1 ? 's' : '' ?></span>
        </div>
        <?php if($booking['kids'] > 0): ?>
        <div class="summary-row">
            <span class="label">Kids</span>
            <span class="value"><?= $booking['kids'] ?> (50% off)</span>
        </div>
        <?php endif; ?>

        <div class="summary-total">
            <span class="label">Total</span>
            <span class="amount">PKR <?= number_format($booking['total'], 2) ?></span>
        </div>
    </div>

</div>

<script>
function formatCard(input) {
    let val = input.value.replace(/\D/g, '').substring(0, 16);
    let formatted = val.match(/.{1,4}/g)?.join(' ') || '';
    input.value = formatted;
    document.getElementById('cardNum').textContent = formatted || '•••• •••• •••• ••••';
}

function formatExpiry(input) {
    let val = input.value.replace(/\D/g, '').substring(0, 4);
    if(val.length >= 2) val = val.substring(0,2) + '/' + val.substring(2);
    input.value = val;
    document.getElementById('cardExp').textContent = val || 'MM/YY';
}
</script>

</body>
</html>