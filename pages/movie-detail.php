<?php
session_start();
require '../config/database.php';

// get movie id from URL
$id = $_GET['id'] ?? 0;

// fetch movie
$stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

// redirect if movie not found
if(!$movie){
    header('Location: movies.php');
    exit();
}

// fetch shows for this movie
$shows_stmt = mysqli_prepare($conn, "SELECT shows.*, theaters.name as theater_name 
                                      FROM shows 
                                      JOIN theaters ON shows.theater_id = theaters.id
                                      WHERE shows.movie_id = ?
                                      ORDER BY shows.show_date, shows.show_time");
mysqli_stmt_bind_param($shows_stmt, 'i', $id);
mysqli_stmt_execute($shows_stmt);
$shows = mysqli_stmt_get_result($shows_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $movie['title'] ?> – CinemAura</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/homenavfoot.css"/>
    <style>
        body { padding-top: 80px; }

        /* ── HERO ── */
        .movie-hero {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }

        .hero-backdrop {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 40%, rgba(232,184,75,0.06) 0%, transparent 60%),
                linear-gradient(to bottom, rgba(8,10,14,0.3) 0%, rgba(8,10,14,0.95) 100%),
                #080a0e;
        }

        <?php if($movie['poster']): ?>
        .hero-backdrop {
            background-image: url('../assets/images/<?= $movie['poster'] ?>');
            background-size: cover;
            background-position: center top;
        }
        .hero-backdrop::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(8,10,14,0.5) 0%, rgba(8,10,14,0.98) 100%);
        }
        <?php endif; ?>

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 60px 48px;
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 48px;
            align-items: end;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .hero-poster {
            width: 220px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid rgba(232,184,75,0.2);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        .hero-poster-placeholder {
            width: 220px;
            aspect-ratio: 2/3;
            background: #161922;
            border-radius: 4px;
            border: 1px solid #1e222c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            opacity: 0.4;
        }

        .hero-info {}

        .hero-eyebrow {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .hero-eyebrow::before {
            content: '';
            display: block;
            width: 24px; height: 1px;
            background: var(--gold);
        }

        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(48px, 6vw, 80px);
            letter-spacing: 2px;
            color: var(--text);
            line-height: 0.95;
            margin-bottom: 20px;
        }

        .hero-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .tag {
            padding: 5px 14px;
            border-radius: 2px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--text-muted);
        }
        .tag.gold {
            background: rgba(232,184,75,0.1);
            border-color: rgba(232,184,75,0.3);
            color: var(--gold);
        }

        .hero-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .rating-stars {
            font-size: 18px;
            color: var(--gold);
        }
        .rating-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: var(--gold);
            letter-spacing: 1px;
        }
        .rating-max {
            font-size: 14px;
            color: var(--text-muted);
        }

        .hero-desc {
            font-size: 15px;
            line-height: 1.8;
            color: var(--text-muted);
            max-width: 560px;
            margin-bottom: 32px;
        }

        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 3px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-gold { background: var(--gold); color: #080a0e; }
        .btn-gold:hover { background: #f0c55a; transform: translateY(-1px); }
        .btn-outline {
            background: transparent;
            border: 1px solid rgba(232,184,75,0.4);
            color: var(--gold);
        }
        .btn-outline:hover { background: rgba(232,184,75,0.08); }

        /* ── CONTENT ── */
        .movie-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 48px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 48px;
        }

        /* trailer */
        .section-label {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-label::before {
            content: '';
            display: block;
            width: 20px; height: 1px;
            background: var(--gold);
        }

        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            letter-spacing: 2px;
            color: var(--text);
            margin-bottom: 20px;
        }
        .section-title span { color: var(--gold); }

        .trailer-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            background: #0f1118;
            border: 1px solid #1e222c;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 48px;
        }
        .trailer-wrap iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .no-trailer {
            width: 100%;
            aspect-ratio: 16/9;
            background: #0f1118;
            border: 1px solid #1e222c;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 48px;
        }
        .no-trailer span { font-size: 40px; opacity: 0.3; }

        /* shows sidebar */
        .shows-sidebar {}
        .shows-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .show-item {
            background: #0f1118;
            border: 1px solid #1e222c;
            padding: 16px 20px;
            transition: background 0.2s;
        }
        .show-item:hover { background: #161922; }
        .show-item-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .show-item-time {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: var(--gold);
            letter-spacing: 1px;
        }
        .show-item-date {
            font-size: 12px;
            color: var(--text-muted);
        }
        .show-item-theater {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }
        .show-item-prices {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .price-tag {
            font-size: 11px;
            padding: 3px 8px;
            background: rgba(232,184,75,0.08);
            border: 1px solid rgba(232,184,75,0.15);
            border-radius: 2px;
            color: var(--gold);
        }
        .btn-book-show {
            display: block;
            text-align: center;
            padding: 8px;
            background: var(--gold);
            color: #080a0e;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-book-show:hover { background: #f0c55a; }

        .no-shows {
            background: #0f1118;
            border: 1px solid #1e222c;
            padding: 32px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        @media(max-width: 768px){
            .hero-content { grid-template-columns: 1fr; padding: 40px 24px; }
            .hero-poster, .hero-poster-placeholder { display: none; }
            .movie-content { grid-template-columns: 1fr; padding: 40px 24px; }
        }
    </style>
</head>
<body>

<?php require '../includes/header.php'; ?>

<!-- HERO -->
<div class="movie-hero">
    <div class="hero-backdrop"></div>
    <div class="hero-content">

        <?php if($movie['poster']): ?>
            <img src="../assets/images/<?= $movie['poster'] ?>" alt="<?= $movie['title'] ?>" class="hero-poster"/>
        <?php else: ?>
            <div class="hero-poster-placeholder">🎬</div>
        <?php endif; ?>

        <div class="hero-info">
            <div class="hero-eyebrow"><?= $movie['status'] === 'showing' ? 'Now Showing' : 'Coming Soon' ?></div>
            <h1 class="hero-title"><?= $movie['title'] ?></h1>

            <div class="hero-tags">
                <span class="tag gold"><?= $movie['genre'] ?></span>
                <span class="tag"><?= $movie['year'] ?></span>
                <span class="tag"><?= $movie['duration'] ?> min</span>
            </div>

            <div class="hero-rating">
                <span class="rating-stars">★</span>
                <span class="rating-num"><?= $movie['rating'] ?></span>
                <span class="rating-max">/ 10</span>
            </div>

            <p class="hero-desc"><?= $movie['description'] ?></p>

            <div class="hero-actions">
                <?php if($movie['status'] === 'showing'): ?>
                    <a href="booking.php" class="btn btn-gold">🎟️ Book Now</a>
                <?php endif; ?>
                <a href="movies.php" class="btn btn-outline">← All Movies</a>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="movie-content">

    <!-- LEFT — TRAILER -->
    <div>
        <div class="section-label">Watch</div>
        <h2 class="section-title">Official <span>Trailer</span></h2>

        <?php if($movie['trailer_url']): ?>
            <?php
            // extract youtube video id
            preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $movie['trailer_url'], $matches);
            $video_id = $matches[1] ?? '';
            ?>
            <?php if($video_id): ?>
                <div class="trailer-wrap">
                    <iframe src="https://www.youtube.com/embed/<?= $video_id ?>" allowfullscreen></iframe>
                </div>
            <?php else: ?>
                <div class="no-trailer">
                    <span>🎬</span>
                    <p>Trailer not available</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-trailer">
                <span>🎬</span>
                <p>No trailer available yet</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- RIGHT — SHOWS -->
    <div class="shows-sidebar">
        <div class="section-label">Showtimes</div>
        <h2 class="section-title">Available <span>Shows</span></h2>

        <div class="shows-list">
            <?php
            $show_count = 0;
            while($show = mysqli_fetch_assoc($shows)):
            $show_count++;
            ?>
            <div class="show-item">
                <div class="show-item-top">
                    <div class="show-item-time"><?= date('h:i A', strtotime($show['show_time'])) ?></div>
                    <div class="show-item-date"><?= date('M d', strtotime($show['show_date'])) ?></div>
                </div>
                <div class="show-item-theater">🏛️ <?= $show['theater_name'] ?></div>
                <div class="show-item-prices">
                    <span class="price-tag">Gold PKR <?= $show['gold_price'] ?></span>
                    <span class="price-tag">Plat PKR <?= $show['platinum_price'] ?></span>
                    <span class="price-tag">Box PKR <?= $show['box_price'] ?></span>
                </div>
                <a href="booking.php" class="btn-book-show">Book This Show</a>
            </div>
            <?php endwhile; ?>

            <?php if($show_count === 0): ?>
                <div class="no-shows">
                    No shows scheduled yet.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require '../includes/footer.php'; ?>

</body>
</html>