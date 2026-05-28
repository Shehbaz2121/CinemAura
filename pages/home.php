<?php session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CinemAura – Book Your Experience</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/css/homenavfoot.css">
  <style>

  </style>
</head>
<body>
<?php require '../includes/header.php'; ?>
<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>

  <div class="film-strip">
    <?php for($i = 0; $i < 12; $i++): ?>
      <div class="strip-hole-row">
        <?php for($j = 0; $j < 6; $j++): ?>
          <div class="hole"></div>
        <?php endfor; ?>
      </div>
      <div class="strip-frame"></div>
    <?php endfor; ?>
  </div>

  <div class="hero-content">
    <div class="hero-eyebrow">Now Showing</div>
    <h1>THE <span class="accent">CINEMA</span> EXPERIENCE REIMAGINED</h1>
    <p class="hero-desc">
      Book your seats in seconds. Watch trailers, read reviews, and choose from Gold, Platinum or Box — all from your couch.
    </p>
    <div class="hero-actions">
      <a href="movies.php" class="btn btn-gold">Browse Movies</a>
      <a href="../auth/register.php" class="btn btn-outline">Create Account</a>
    </div>
    <div class="hero-stats">
      <div>
        <div class="stat-num">50+</div>
        <div class="stat-label">Movies</div>
      </div>
      <div>
        <div class="stat-num">12</div>
        <div class="stat-label">Theaters</div>
      </div>
      <div>
        <div class="stat-num">3</div>
        <div class="stat-label">Seat Classes</div>
      </div>
    </div>
  </div>
</section>

<!-- MARQUEE -->
<div class="marquee-wrap">
  <div class="marquee-track">
    <?php
      $items = ['Now Showing', 'Book Tickets', 'Gold Class', 'Platinum Class', 'Box Class', 'Watch Trailers', 'Read Reviews', 'Kids Discounts'];
      $repeated = array_merge($items, $items);
      foreach($repeated as $item):
    ?>
      <span><?= $item ?></span><span class="sep">✦</span>
    <?php endforeach; ?>
  </div>
</div>

<!-- NOW SHOWING -->
<section class="section">
  <div class="section-header">
    <h2 class="section-title">Now <span>Showing</span></h2>
    <a href="pages/movies.php" class="section-link">View All Movies</a>
  </div>
  <div class="movies-grid">
    <?php
      $movies = [
        ['title' => 'Movie Title', 'genre' => 'Action', 'rating' => '8.2', 'year' => '2024', 'icon' => '🎬'],
        ['title' => 'Movie Title', 'genre' => 'Drama',  'rating' => '7.9', 'year' => '2024', 'icon' => '🎭'],
        ['title' => 'Movie Title', 'genre' => 'Sci-Fi',  'rating' => '9.1', 'year' => '2024', 'icon' => '🚀'],
        ['title' => 'Movie Title', 'genre' => 'Horror',  'rating' => '7.4', 'year' => '2024', 'icon' => '👁️'],
      ];
      foreach($movies as $i => $m):
    ?>
    <div class="movie-card" style="animation-delay: <?= $i * 0.1 ?>s">
      <div class="movie-poster-placeholder">
        <div class="poster-icon"><?= $m['icon'] ?></div>
        <div class="poster-genre"><?= $m['genre'] ?></div>
      </div>
      <div class="movie-rating">★ <?= $m['rating'] ?></div>
      <div class="movie-overlay">
        <div class="movie-title"><?= $m['title'] ?></div>
        <div class="movie-meta"><?= $m['genre'] ?> · <?= $m['year'] ?></div>
        <a href="pages/booking.php" class="btn btn-gold" style="font-size:11px; padding:8px 18px;">Book Now</a>
      </div>
      <div class="card-bottom">
        <div class="card-title-visible"><?= $m['title'] ?></div>
        <div class="card-sub"><?= $m['genre'] ?> · <?= $m['year'] ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- SEAT CLASSES -->
<section class="classes-section">
  <div class="section-header">
    <h2 class="section-title">Choose Your <span>Class</span></h2>
  </div>
  <div class="classes-grid">
    <div class="class-card">
      <div class="class-icon">🥇</div>
      <div class="class-name">Gold</div>
      <div class="class-desc">Premium recliner seats with extra legroom and dedicated service.</div>
      <div class="class-price">PKR 800</div>
    </div>
    <div class="class-card featured">
      <div class="class-badge">Most Popular</div>
      <div class="class-icon">💎</div>
      <div class="class-name">Platinum</div>
      <div class="class-desc">Ultra-luxury pods with in-seat dining and a private lounge.</div>
      <div class="class-price">PKR 1,400</div>
    </div>
    <div class="class-card">
      <div class="class-icon">📦</div>
      <div class="class-name">Box</div>
      <div class="class-desc">Private viewing boxes, perfect for families and groups.</div>
      <div class="class-price">PKR 2,500</div>
    </div>
  </div>
</section>

<section class="section">
  <div class="section-header">
    <h2 class="section-title">How It <span>Works</span></h2>
  </div>
  <div class="steps-grid">
    <div class="step">
      <div class="step-num">01</div>
      <div class="step-title">Browse Movies</div>
      <div class="step-desc">Explore what's showing, watch trailers and read reviews.</div>
      <div class="step-line"></div>
    </div>
    <div class="step">
      <div class="step-num">02</div>
      <div class="step-title">Pick a Showtime</div>
      <div class="step-desc">Select your preferred date, theater and show time.</div>
      <div class="step-line"></div>
    </div>
    <div class="step">
      <div class="step-num">03</div>
      <div class="step-title">Choose Your Seat</div>
      <div class="step-desc">Pick from Gold, Platinum or Box class seating.</div>
      <div class="step-line"></div>
    </div>
    <div class="step">
      <div class="step-num">04</div>
      <div class="step-title">Confirm & Go</div>
      <div class="step-desc">Complete your booking and enjoy the experience.</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2>READY TO <span>BOOK?</span></h2>
  <p>Create a free account and start booking your cinema experience today.</p>
  <div class="cta-actions">
    <a href="auth/register.php" class="btn btn-gold">Get Started Free</a>
    <a href="pages/movies.php" class="btn btn-outline">Browse Movies</a>
  </div>
</section>

<?php require '../includes/footer.php'; ?>

</body>
</html>