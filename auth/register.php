<?php
session_start();
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

              $new_id = mysqli_insert_id($conn);
    
   
               $_SESSION['user_id']   = $new_id;
               $_SESSION['user_name'] = $full_name;
               $_SESSION['user_role'] = 'user';
    
    
                header('Location: ../pages/home.php');
                exit();
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
  <link rel="stylesheet" href="../assets/css/register.css">
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
