<?php
// pages/home.php
// CinemAura Landing Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CinemAura – Book Your Experience</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --gold: #e8b84b;
      --gold-dim: #c9982a;
      --bg: #080a0e;
      --bg2: #0f1118;
      --bg3: #161922;
      --text: #f0ece2;
      --text-muted: #7a7568;
      --red: #c0392b;
    }

    html { scroll-behavior: smooth; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
      overflow-x: hidden;
    }

    /* ── NOISE OVERLAY ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
      opacity: 0.03;
      pointer-events: none;
      z-index: 999;
    }

    /* ── NAV ── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 22px 48px;
      background: linear-gradient(to bottom, rgba(8,10,14,0.95), transparent);
    }

    .logo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      letter-spacing: 3px;
      color: var(--gold);
      text-decoration: none;
    }
    .logo span { color: var(--text); }

    .nav-links { display: flex; gap: 36px; list-style: none; }
    .nav-links a {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 13px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--gold); }

    .nav-actions { display: flex; gap: 12px; }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 24px;
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
    .btn-outline {
      background: transparent;
      border: 1px solid rgba(232,184,75,0.4);
      color: var(--gold);
    }
    .btn-outline:hover {
      background: rgba(232,184,75,0.08);
      border-color: var(--gold);
    }
    .btn-gold {
      background: var(--gold);
      color: #080a0e;
    }
    .btn-gold:hover { background: #f0c55a; }

    /* ── HERO ── */
    .hero {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      overflow: hidden;
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 60% 40%, rgba(232,184,75,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 80% 70%, rgba(192,57,43,0.08) 0%, transparent 50%),
        var(--bg);
    }

    .film-strip {
      position: absolute;
      right: -20px;
      top: 0; bottom: 0;
      width: 420px;
      display: flex;
      flex-direction: column;
      gap: 0;
      opacity: 0.18;
      overflow: hidden;
    }
    .strip-hole-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 12px;
    }
    .hole {
      width: 18px; height: 18px;
      border-radius: 3px;
      background: var(--bg2);
      border: 1px solid #2a2d35;
    }
    .strip-frame {
      height: 120px;
      background: var(--bg3);
      border-top: 1px solid #2a2d35;
      border-bottom: 1px solid #2a2d35;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 48px;
      max-width: 680px;
      animation: fadeUp 0.9s ease both;
    }

    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-size: 11px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 24px;
    }
    .hero-eyebrow::before {
      content: '';
      display: block;
      width: 32px; height: 1px;
      background: var(--gold);
    }

    h1 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(72px, 10vw, 120px);
      line-height: 0.9;
      letter-spacing: 2px;
      color: var(--text);
      margin-bottom: 28px;
    }
    h1 .accent { color: var(--gold); }

    .hero-desc {
      font-size: 16px;
      line-height: 1.8;
      color: var(--text-muted);
      max-width: 440px;
      margin-bottom: 44px;
    }

    .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }

    .hero-stats {
      display: flex;
      gap: 48px;
      margin-top: 64px;
      padding-top: 40px;
      border-top: 1px solid rgba(255,255,255,0.06);
    }
    .stat-num {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 36px;
      color: var(--gold);
      letter-spacing: 1px;
    }
    .stat-label {
      font-size: 11px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-top: 2px;
    }

    /* ── MARQUEE ── */
    .marquee-wrap {
      background: var(--gold);
      padding: 14px 0;
      overflow: hidden;
      white-space: nowrap;
    }
    .marquee-track {
      display: inline-block;
      animation: marquee 22s linear infinite;
    }
    .marquee-track span {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 15px;
      letter-spacing: 4px;
      color: #080a0e;
      padding: 0 32px;
    }
    .marquee-track span.sep { color: rgba(8,10,14,0.3); }

    /* ── NOW SHOWING ── */
    .section { padding: 100px 48px; }
    .section-header {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      margin-bottom: 48px;
    }
    .section-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 48px;
      letter-spacing: 2px;
      color: var(--text);
    }
    .section-title span { color: var(--gold); }
    .section-link {
      font-size: 12px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--gold);
      text-decoration: none;
      border-bottom: 1px solid rgba(232,184,75,0.3);
      padding-bottom: 2px;
      transition: border-color 0.2s;
    }
    .section-link:hover { border-color: var(--gold); }

    .movies-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 24px;
    }

    .movie-card {
      position: relative;
      border-radius: 4px;
      overflow: hidden;
      cursor: pointer;
      animation: fadeUp 0.6s ease both;
    }
    .movie-card:nth-child(2) { animation-delay: 0.1s; }
    .movie-card:nth-child(3) { animation-delay: 0.2s; }
    .movie-card:nth-child(4) { animation-delay: 0.3s; }

    .movie-poster {
      width: 100%;
      aspect-ratio: 2/3;
      object-fit: cover;
      display: block;
      background: var(--bg3);
      position: relative;
    }

    .movie-poster-placeholder {
      width: 100%;
      aspect-ratio: 2/3;
      background: var(--bg3);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      border: 1px solid #1e222c;
    }
    .poster-icon { font-size: 32px; opacity: 0.3; }
    .poster-genre {
      font-size: 10px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text-muted);
    }

    .movie-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(8,10,14,0.98) 0%, rgba(8,10,14,0.4) 50%, transparent 100%);
      opacity: 0;
      transition: opacity 0.3s;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 20px;
    }
    .movie-card:hover .movie-overlay { opacity: 1; }

    .movie-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 20px;
      letter-spacing: 1px;
      color: var(--text);
      margin-bottom: 4px;
    }
    .movie-meta {
      font-size: 11px;
      color: var(--text-muted);
      letter-spacing: 1px;
      margin-bottom: 14px;
    }
    .movie-rating {
      position: absolute;
      top: 12px; right: 12px;
      background: rgba(8,10,14,0.85);
      border: 1px solid rgba(232,184,75,0.4);
      color: var(--gold);
      font-size: 12px;
      font-weight: 500;
      padding: 4px 10px;
      border-radius: 2px;
    }

    .card-bottom {
      padding: 14px 0 0;
    }
    .card-title-visible {
      font-size: 14px;
      font-weight: 500;
      color: var(--text);
      margin-bottom: 4px;
    }
    .card-sub {
      font-size: 12px;
      color: var(--text-muted);
    }

    /* ── SEAT CLASSES ── */
    .classes-section {
      background: var(--bg2);
      padding: 100px 48px;
    }
    .classes-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2px;
      margin-top: 48px;
    }
    .class-card {
      padding: 48px 36px;
      background: var(--bg3);
      position: relative;
      overflow: hidden;
      transition: background 0.3s;
      cursor: pointer;
    }
    .class-card:hover { background: #1a1e28; }
    .class-card.featured {
      background: var(--gold);
    }
    .class-card.featured:hover { background: #f0c55a; }

    .class-icon {
      font-size: 28px;
      margin-bottom: 20px;
    }
    .class-name {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 36px;
      letter-spacing: 2px;
      color: var(--text);
      margin-bottom: 8px;
    }
    .class-card.featured .class-name { color: #080a0e; }
    .class-desc {
      font-size: 14px;
      line-height: 1.7;
      color: var(--text-muted);
      margin-bottom: 24px;
    }
    .class-card.featured .class-desc { color: rgba(8,10,14,0.65); }
    .class-price {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      color: var(--gold);
    }
    .class-card.featured .class-price { color: #080a0e; }
    .class-badge {
      position: absolute;
      top: 20px; right: 20px;
      background: #080a0e;
      color: var(--gold);
      font-size: 10px;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 2px;
    }

    /* ── HOW IT WORKS ── */
    .steps-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 40px;
      margin-top: 64px;
    }
    .step {
      position: relative;
      animation: fadeUp 0.6s ease both;
    }
    .step:nth-child(2) { animation-delay: 0.1s; }
    .step:nth-child(3) { animation-delay: 0.2s; }
    .step:nth-child(4) { animation-delay: 0.3s; }

    .step-num {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 72px;
      color: rgba(232,184,75,0.1);
      line-height: 1;
      margin-bottom: 16px;
    }
    .step-title {
      font-size: 16px;
      font-weight: 500;
      color: var(--text);
      margin-bottom: 10px;
    }
    .step-desc {
      font-size: 14px;
      line-height: 1.7;
      color: var(--text-muted);
    }
    .step-line {
      position: absolute;
      top: 36px; right: -20px;
      width: 40px; height: 1px;
      background: rgba(232,184,75,0.2);
    }
    .step:last-child .step-line { display: none; }

    /* ── CTA ── */
    .cta-section {
      background: var(--bg2);
      padding: 100px 48px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .cta-section::before {
      content: '';
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      width: 600px; height: 300px;
      background: radial-gradient(ellipse, rgba(232,184,75,0.08) 0%, transparent 70%);
      pointer-events: none;
    }
    .cta-section h2 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(48px, 7vw, 80px);
      letter-spacing: 3px;
      margin-bottom: 20px;
      position: relative;
    }
    .cta-section h2 span { color: var(--gold); }
    .cta-section p {
      font-size: 16px;
      color: var(--text-muted);
      margin-bottom: 40px;
      max-width: 460px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.8;
      position: relative;
    }
    .cta-actions { display: flex; gap: 16px; justify-content: center; position: relative; }

    /* ── FOOTER ── */
    footer {
      padding: 48px;
      border-top: 1px solid rgba(255,255,255,0.05);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .footer-copy {
      font-size: 12px;
      color: var(--text-muted);
      letter-spacing: 1px;
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes marquee {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }

    @media (max-width: 768px) {
      nav { padding: 18px 24px; }
      .nav-links { display: none; }
      .hero-content { padding: 0 24px; }
      .section, .classes-section, .cta-section { padding: 64px 24px; }
      .classes-grid, .steps-grid { grid-template-columns: 1fr; }
      footer { flex-direction: column; gap: 16px; text-align: center; }
    }
  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="logo">Cinem<span>Aura</span></a>
  <ul class="nav-links">
    <li><a href="#">Movies</a></li>
    <li><a href="#">Theaters</a></li>
    <li><a href="#">Showtimes</a></li>
    <li><a href="#">Reviews</a></li>
  </ul>
  <div class="nav-actions">
    <a href="auth/login.php" class="btn btn-outline">Sign In</a>
    <a href="auth/register.php" class="btn btn-gold">Register</a>
  </div>
</nav>

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
      <a href="pages/movies.php" class="btn btn-gold">Browse Movies</a>
      <a href="auth/register.php" class="btn btn-outline">Create Account</a>
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

<!-- HOW IT WORKS -->
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

<!-- FOOTER -->
<footer>
  <a href="#" class="logo">Cinem<span>Aura</span></a>
  <div class="footer-copy">© <?= date('Y') ?> CinemAura. All rights reserved.</div>
</footer>

</body>
</html>