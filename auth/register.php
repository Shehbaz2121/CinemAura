<?php

require '../config/database.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    // Validation
    if (empty($full_name))                        $errors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (strlen($password) < 6)                    $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                   $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'An account with this email already exists.';
        } else {
            // Hash password and insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert, 'sss', $full_name, $email, $hashed);

            if (mysqli_stmt_execute($insert)) {
                $success = 'Account created! <a href="login.php">Sign in now →</a>';
            } else {
                $errors[] = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register – CinemAura</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
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
      --red: #e05555;
      --green: #4caf82;
      --border: #1e222c;
    }

    html, body {
      min-height: 100vh;
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
    }

    /* noise */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
      opacity: 0.03; pointer-events: none; z-index: 999;
    }

    .page {
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 100vh;
    }

    /* ── LEFT PANEL ── */
    .left {
      position: relative;
      background: var(--bg2);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 48px;
      overflow: hidden;
    }

    .left-bg {
      position: absolute; inset: 0;
      background:
        radial-gradient(ellipse 70% 50% at 30% 60%, rgba(232,184,75,0.08) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 70% 20%, rgba(192,57,43,0.06) 0%, transparent 50%);
      pointer-events: none;
    }

    /* vertical film strip */
    .film-strip {
      position: absolute;
      right: 0; top: 0; bottom: 0;
      width: 48px;
      display: flex;
      flex-direction: column;
      gap: 0;
      opacity: 0.15;
    }
    .strip-seg {
      flex: 1;
      border-left: 1px solid #2a2d35;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-around;
      padding: 4px 0;
    }
    .hole {
      width: 14px; height: 14px;
      border-radius: 2px;
      border: 1px solid #3a3d45;
      background: var(--bg);
    }

    .left-logo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 32px;
      letter-spacing: 3px;
      color: var(--gold);
      text-decoration: none;
      position: relative;
    }
    .left-logo span { color: var(--text); }

    .left-middle { position: relative; }

    .left-eyebrow {
      font-size: 11px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .left-eyebrow::before {
      content: '';
      display: block;
      width: 28px; height: 1px;
      background: var(--gold);
    }

    .left-title {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(52px, 6vw, 72px);
      line-height: 0.92;
      letter-spacing: 2px;
      color: var(--text);
      margin-bottom: 24px;
    }
    .left-title span { color: var(--gold); }

    .left-desc {
      font-size: 15px;
      line-height: 1.8;
      color: var(--text-muted);
      max-width: 340px;
    }

    .left-perks {
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-top: 40px;
    }
    .perk {
      display: flex;
      align-items: center;
      gap: 14px;
      font-size: 14px;
      color: var(--text-muted);
    }
    .perk-icon {
      width: 36px; height: 36px;
      border-radius: 3px;
      background: rgba(232,184,75,0.08);
      border: 1px solid rgba(232,184,75,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    .left-bottom {
      font-size: 12px;
      color: var(--text-muted);
      letter-spacing: 1px;
      position: relative;
    }

    /* ── RIGHT PANEL ── */
    .right {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 64px;
      background: var(--bg);
    }

    .form-wrap {
      width: 100%;
      max-width: 420px;
      animation: fadeUp 0.7s ease both;
    }

    .form-heading {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 42px;
      letter-spacing: 2px;
      color: var(--text);
      margin-bottom: 6px;
    }
    .form-sub {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 36px;
    }
    .form-sub a {
      color: var(--gold);
      text-decoration: none;
    }
    .form-sub a:hover { text-decoration: underline; }

    /* alerts */
    .alert {
      padding: 14px 18px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 24px;
      line-height: 1.6;
    }
    .alert-error {
      background: rgba(224,85,85,0.08);
      border: 1px solid rgba(224,85,85,0.25);
      color: var(--red);
    }
    .alert-success {
      background: rgba(76,175,130,0.08);
      border: 1px solid rgba(76,175,130,0.25);
      color: var(--green);
    }
    .alert-success a { color: var(--gold); }
    .alert ul { padding-left: 16px; }

    /* form fields */
    .field {
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-size: 11px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 8px;
    }
    input {
      width: 100%;
      padding: 13px 16px;
      background: var(--bg2);
      border: 1px solid var(--border);
      border-radius: 3px;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 300;
      transition: border-color 0.2s, background 0.2s;
      outline: none;
    }
    input:focus {
      border-color: rgba(232,184,75,0.5);
      background: var(--bg3);
    }
    input::placeholder { color: #3a3d45; }

    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .password-wrap { position: relative; }
    .toggle-pw {
      position: absolute;
      right: 14px; top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-muted);
      cursor: pointer;
      font-size: 16px;
      padding: 0;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--gold); }

    .strength-bar {
      height: 3px;
      background: var(--border);
      border-radius: 2px;
      margin-top: 8px;
      overflow: hidden;
    }
    .strength-fill {
      height: 100%;
      width: 0%;
      border-radius: 2px;
      transition: width 0.3s, background 0.3s;
    }
    .strength-label {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .divider {
      border: none;
      border-top: 1px solid var(--border);
      margin: 24px 0;
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      background: var(--gold);
      color: #080a0e;
      border: none;
      border-radius: 3px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 500;
      letter-spacing: 2px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-submit:hover { background: #f0c55a; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    .terms {
      font-size: 12px;
      color: var(--text-muted);
      text-align: center;
      margin-top: 16px;
      line-height: 1.6;
    }
    .terms a { color: var(--gold); text-decoration: none; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .page { grid-template-columns: 1fr; }
      .left { display: none; }
      .right { padding: 48px 24px; }
    }
  </style>
</head>
<body>
<div class="page">

  <!-- LEFT -->
  <div class="left">
    <div class="left-bg"></div>

    <div class="film-strip">
      <?php for($i=0;$i<20;$i++): ?>
        <div class="strip-seg">
          <div class="hole"></div>
          <div class="hole"></div>
        </div>
      <?php endfor; ?>
    </div>

    <a href="../index.php" class="left-logo">Cinem<span>Aura</span></a>

    <div class="left-middle">
      <div class="left-eyebrow">Join Us</div>
      <h2 class="left-title">YOUR <span>SEAT</span> AWAITS</h2>
      <p class="left-desc">Create your free account and get instant access to movies, showtimes, trailers and exclusive seat class bookings.</p>
      <div class="left-perks">
        <div class="perk"><div class="perk-icon">🎬</div> Browse movies & watch trailers</div>
        <div class="perk"><div class="perk-icon">🪑</div> Book Gold, Platinum or Box seats</div>
        <div class="perk"><div class="perk-icon">👦</div> Special discounts for kids</div>
        <div class="perk"><div class="perk-icon">⭐</div> Read & write movie reviews</div>
      </div>
    </div>

    <div class="left-bottom">© <?= date('Y') ?> CinemAura</div>
  </div>

  <!-- RIGHT -->
  <div class="right">
    <div class="form-wrap">
      <h1 class="form-heading">Create Account</h1>
      <p class="form-sub">Already have one? <a href="login.php">Sign in →</a></p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <ul>
            <?php foreach($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" action="">

        <div class="field">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name"
                 placeholder="e.g. Ahmed Khan"
                 value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                 required/>
        </div>

        <div class="field">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email"
                 placeholder="you@example.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required/>
        </div>

        <div class="field-row">
          <div class="field">
            <label for="password">Password</label>
            <div class="password-wrap">
              <input type="password" id="password" name="password"
                     placeholder="Min. 6 characters"
                     oninput="checkStrength(this.value)"
                     required/>
              <button type="button" class="toggle-pw" onclick="togglePw('password', this)">👁</button>
            </div>
            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
            <div class="strength-label" id="strengthLabel"></div>
          </div>

          <div class="field">
            <label for="confirm_password">Confirm Password</label>
            <div class="password-wrap">
              <input type="password" id="confirm_password" name="confirm_password"
                     placeholder="Repeat password"
                     required/>
              <button type="button" class="toggle-pw" onclick="togglePw('confirm_password', this)">👁</button>
            </div>
          </div>
        </div>

        <hr class="divider"/>

        <button type="submit" class="btn-submit">Create My Account</button>

        <p class="terms">
          By registering you agree to our
          <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
        </p>

      </form>
    </div>
  </div>

</div>

<script>
function togglePw(id, btn) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '👁' : '🙈';
}

function checkStrength(val) {
  const fill  = document.getElementById('strengthFill');
  const label = document.getElementById('strengthLabel');
  let score = 0;
  if (val.length >= 6)  score++;
  if (val.length >= 10) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { w: '0%',   c: 'transparent', t: '' },
    { w: '25%',  c: '#e05555',     t: 'Weak' },
    { w: '50%',  c: '#e07b39',     t: 'Fair' },
    { w: '75%',  c: '#d4a017',     t: 'Good' },
    { w: '100%', c: '#4caf82',     t: 'Strong' },
  ];
  const l = levels[Math.min(score, 4)];
  fill.style.width      = l.w;
  fill.style.background = l.c;
  label.textContent     = l.t;
  label.style.color     = l.c;
}
</script>
</body>
</html>
