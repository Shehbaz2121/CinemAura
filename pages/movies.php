<?php
require '../config/database.php';

// filter by genre if selected
$genre_filter = $_GET['genre'] ?? '';

if($genre_filter){
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE status = 'showing' AND genre = ?");
    mysqli_stmt_bind_param($stmt, 's', $genre_filter);
    mysqli_stmt_execute($stmt);
    $movies = mysqli_stmt_get_result($stmt);
} else {
    $movies = mysqli_query($conn, "SELECT * FROM movies WHERE status = 'showing'");
}

// fetch upcoming movies
$upcoming = mysqli_query($conn, "SELECT * FROM movies WHERE status = 'upcoming'");

// fetch all genres for filter
$genres = mysqli_query($conn, "SELECT DISTINCT genre FROM movies WHERE status = 'showing'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Movies – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
    <link rel="stylesheet" href="../assets/css/movies.css">
</head>
<body>

<?php require '../includes/header.php'; ?>

<div class="page-wrap">

    <div class="page-eyebrow">Now Showing</div>
    <h1 class="page-title">All <span>Movies</span></h1>
    <p class="page-sub">Browse all currently showing movies and book your seats.</p>

    <!-- GENRE FILTERS -->
    <div class="genre-filters">
        <a href="movies.php" class="genre-btn <?= !$genre_filter ? 'active' : '' ?>">All</a>
        <?php while($g = mysqli_fetch_assoc($genres)): ?>
            <a href="?genre=<?= urlencode($g['genre']) ?>" 
               class="genre-btn <?= $genre_filter === $g['genre'] ? 'active' : '' ?>">
                <?= $g['genre'] ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- NOW SHOWING -->
    <div class="movies-grid">
        <?php 
        $count = 0;
        while($movie = mysqli_fetch_assoc($movies)): 
        $count++;
        ?>
        <div class="movie-card" style="animation-delay: <?= $count * 0.05 ?>s">

            <?php if($movie['poster']): ?>
                <img src="../assets/images/<?= $movie['poster'] ?>" alt="<?= $movie['title'] ?>" class="movie-poster"/>
            <?php else: ?>
                <div class="movie-poster-placeholder">
                    <div class="poster-icon">🎬</div>
                    <div class="poster-genre-label"><?= $movie['genre'] ?></div>
                </div>
            <?php endif; ?>

            <div class="movie-rating">★ <?= $movie['rating'] ?></div>

            <div class="movie-overlay">
                <div class="overlay-title"><?= $movie['title'] ?></div>
                <div class="overlay-meta"><?= $movie['genre'] ?> · <?= $movie['year'] ?> · <?= $movie['duration'] ?>min</div>
                <a href="booking.php" class="btn-book-now">Book Now</a>
            </div>

            <div class="movie-info">
                <div class="movie-info-title"><?= $movie['title'] ?></div>
                <div class="movie-info-sub"><?= $movie['genre'] ?> · <?= $movie['year'] ?></div>
            </div>

        </div>
        <?php endwhile; ?>

        <?php if($count === 0): ?>
            <div class="no-movies">
                <h3>No Movies Found</h3>
                <p>No movies available for this genre right now.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- COMING SOON -->
    <?php 
    $upcoming_count = mysqli_num_rows($upcoming);
    if($upcoming_count > 0): ?>
    <div style="margin-top: 80px;">
        <div class="page-eyebrow">Coming Soon</div>
        <h2 class="page-title">Up<span>coming</span></h2>
        <p class="page-sub">Movies arriving soon at CinemAura.</p>

        <div class="movies-grid">
            <?php $i = 0; while($movie = mysqli_fetch_assoc($upcoming)): $i++; ?>
            <div class="movie-card" style="animation-delay: <?= $i * 0.05 ?>s; opacity: 0.8;">

                <?php if($movie['poster']): ?>
                    <img src="../assets/images/<?= $movie['poster'] ?>" alt="<?= $movie['title'] ?>" class="movie-poster"/>
                <?php else: ?>
                    <div class="movie-poster-placeholder">
                        <div class="poster-icon">🎬</div>
                        <div class="poster-genre-label"><?= $movie['genre'] ?></div>
                    </div>
                <?php endif; ?>

                <div class="movie-rating" style="background: rgba(8,10,14,0.9); border-color: rgba(255,255,255,0.2); color: #7a7568;">Soon</div>

                <div class="movie-overlay">
                    <div class="overlay-title"><?= $movie['title'] ?></div>
                    <div class="overlay-meta"><?= $movie['genre'] ?> · <?= $movie['year'] ?></div>
                </div>

                <div class="movie-info">
                    <div class="movie-info-title"><?= $movie['title'] ?></div>
                    <div class="movie-info-sub"><?= $movie['genre'] ?> · Coming <?= $movie['year'] ?></div>
                </div>

            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require '../includes/footer.php'; ?>

</body>
</html>