<?php
require '../config/database.php';
$selected_date = $_GET['date'] ?? date('Y-m-d');

$stmt = mysqli_prepare($conn, "SELECT shows.*, movies.title as movie_title, movies.genre, movies.rating, movies.poster,
                                theaters.name as theater_name, theaters.location
                                FROM shows
                                JOIN movies ON shows.movie_id = movies.id
                                JOIN theaters ON shows.theater_id = theaters.id
                                WHERE shows.show_date = ?
                                ORDER BY shows.show_time ASC");
mysqli_stmt_bind_param($stmt, 's', $selected_date);
mysqli_stmt_execute($stmt);
$shows = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Showtimes – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
    <link rel="stylesheet" href="../assets/css/showtimes.css">
    
</head>
<body>

<?php require '../includes/header.php'; ?>

<div class="page-wrap">

    <div class="page-eyebrow">CinemAura</div>
    <h1 class="page-title">Show <span>Times</span></h1>
    <p class="page-sub">Browse all available shows for your selected date.</p>

    <!-- DATE FILTER -->
    <form method="GET" action="" class="date-filter">
        <label>Select Date</label>
        <input type="date" name="date" value="<?= htmlspecialchars($selected_date) ?>"/>
        <button type="submit">Find Shows</button>
    </form>

    <!-- SHOWS LIST -->
    <div class="shows-list">
        <?php if(mysqli_num_rows($shows) > 0): ?>
            <?php while($show = mysqli_fetch_assoc($shows)): ?>
            <div class="show-card">

                <div class="show-time-block">
                    <div class="show-time"><?= date('h:i', strtotime($show['show_time'])) ?></div>
                    <div class="show-ampm"><?= date('A', strtotime($show['show_time'])) ?></div>
                </div>

                <div class="show-info">
                    <div class="show-movie"><?= $show['movie_title'] ?></div>
                    <div class="show-meta">
                        <span>🏛️ <?= $show['theater_name'] ?></span>
                        <span>📍 <?= $show['location'] ?></span>
                        <span>🎭 <?= $show['genre'] ?></span>
                        <span>⭐ <?= $show['rating'] ?></span>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:12px;">
                    <div class="show-prices">
                        <div class="price-item">Gold: <strong>PKR <?= $show['gold_price'] ?></strong></div>
                        <div class="price-item">Platinum: <strong>PKR <?= $show['platinum_price'] ?></strong></div>
                        <div class="price-item">Box: <strong>PKR <?= $show['box_price'] ?></strong></div>
                    </div>
                    <a href="booking.php" class="btn-book">Book Now</a>
                </div>

            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-shows">
                <h3>No Shows Found</h3>
                <p>There are no shows scheduled for <?= date('M d, Y', strtotime($selected_date)) ?>. Try another date!</p>
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>